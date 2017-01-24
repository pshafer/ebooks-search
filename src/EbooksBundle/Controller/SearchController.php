<?php

namespace EbooksBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
  /**
   * @Route("/", name="search_home")
   */
  public function indexction(Request $request)
  {
    $client = $this->get('solarium.client');

    $search = array();
    $search['types']    = $this->getParameter('ebooks.search_types');
    $search['type']     = 'kw';



    $select['component'] = array();
    $select['component']['facetset'] = array();
    $select['component']['facetset']['facet'][] = array('type' => 'field', 'key' => 'subjects', 'field' => 'subject');

    $query = $client->createSelect($select);


    $results = $client->select($query);

    $response = array();
    $response['response'] = array();
    $response['response']['numFound'] = $results->getNumFound();
    $response['facets']['subjects'] = $this->facetFilterList($results->getFacetSet()->getFacet('subjects')->getValues());

    $subjects = $this->facetFilterList($results->getFacetSet()->getFacet('subjects')->getValues());

    ksort($subjects);

    $form = $this->createFormBuilder(array('csrf_protection' => false))
      ->setAction($this->generateUrl('search_results'))
      ->setMethod('GET')
      ->add('q', TextType::class)
      ->add('search', SubmitType::class)
      ->getForm();

    $form->handleRequest($request);

    return $this->render('EbooksBundle:Default:searchhome.html.twig', array(
      'searchForm' => $form->createView(),
      'numBooks' => $response['response']['numFound'],
      'subjects' => $subjects,
      'search' => $search,
    ));
  }

  /**
   * @Route("/search", name="search_results")
   */
  public function searchAction(Request $request)
  {

    // Initialize the search parameters for this request
    $search                       = array();
    $search['types']              = $this->getParameter('ebooks.search_types');
    $search['type']               = $request->query->has('type') ? $request->get('type') : 'kw';
    $search['field']              = $request->query->has('search-field')? $request->get('search-field'):'all';
    $search['query']              = ($request->query->has('q')  && $request->get('q') != '') ? $request->get('q') : '*';
    $search['query_display']      = ($search['query'] === '*') ? '' : $search['query'];
    $search['sort']               = $request->query->has('sort') ? $request->get('sort') : '';
    $search['result_display']     = $this->getParameter('ebooks.result_display');
    $search['display']            = $request->query->has('results') ? $request->get('results') : $search['result_display']['default_value'];
    $search['filter']             = $request->query->has('filter') ? $request->get('filter') : array();
    $search['query_string']       = $request->query->all();
    $search['current_filters']    = $this->parseCurrentFilters($search['filter'], $search['query_string']);


    // Initialize the pager parameters for this request
    $pager                        = array();
    $pager['display']             = $search['display'];
    $pager['page']                = $request->query->has('page') ? $request->get('page') : 1;
    $pager['start']               = (($pager['page'] - 1) * $pager['display']);


    // Initialize the Solr Client
    $client                       = $this->get('solarium.client');
    $select['query']              = ($search['field'] === 'all') ? $search['query'] : $search['field'] . ':' . $search['query'];
    $select['start']              = $pager['start'];
    $select['rows']               = $pager['display'];
    $select['sort']               = array('title_sort' => 'asc');


    // Set up the Solr search components, e.g. facets sets etc
    $select['component'] = array();
    $select['component']['facetset'] = array();
    $select['component']['facetset']['facet'][] = array('type' => 'field', 'key' => 'subjects', 'field' => 'subject','mincount' => 1);
    $select['component']['facetset']['facet'][] = array('type' => 'field', 'key' => 'vendors', 'field' => 'vendor', 'mincount' => 1);

    $query = $client->createSelect($select);

    // Add each search facet filter query
    // Subject Filter Queries

    $filter_count = 0;
    foreach($search['filter'] as $filter){
      $query->createFilterQuery('filter_' . $filter_count)->setQuery($filter);
      $filter_count++;
    }

    // execute the query
    $results = $client->select($query);

    // set up the page response
    $search_results = array();
    $search_results['numFound'] = $results->getNumFound();
    $search_results['facets']['subject']   = $this->facetFilterList($results->getFacetSet()->getFacet('subjects')->getValues(), $search['query_string']);
    $search_results['facets']['vendor']    = $this->facetFilterList($results->getFacetSet()->getFacet('vendors')->getValues(), $search['query_string']);
    $search_results['documents']            = $results->getDocuments();


    $subjects = $this->facetFilterList($results->getFacetSet()->getFacet('subjects')->getValues());
    $vendors = $this->facetFilterList($results->getFacetSet()->getFacet('vendors')->getValues());
    $documents = $results->getDocuments();

    return $this->render('EbooksBundle:Default:searchresults.html.twig', array(
      'search_results' => $search_results,
      'search_params' => $search,
      'pager' => $pager
    ));
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @Route("/api/getbooks", name="api_getbooks")
   * @Method("get")
   */
  public function queryAction(Request $request)
  {
    $sort = array();
    $sort = '';

    $pager = array();
    $pager['display'] = 20;
    $pager['page'] = 1;
    $pager['start'] = (($pager['page'] - 1) * $pager['display']);

    $client = $this->get('solarium.client');


    $select['start'] = $pager['start'];
    $select['rows'] = $pager['display'];
    $select['sort'] = array('title_sort' => 'asc');
    $select['component'] = array();
    $select['component']['facetset'] = array();
    $select['component']['facetset']['facet'][] = array('type' => 'field', 'key' => 'subjects', 'field' => 'subject');
    $select['component']['facetset']['facet'][] = array('type' => 'field', 'key' => 'vendors', 'field' => 'vendor');

    $query = $client->createSelect($select);

    $results = $client->select($query);

    $response = array();

    $response['response'] = array();

    $response['response']['numFound'] = $results->getNumFound();
    $response['facets']['subjects'] = $this->facetFilterList($results->getFacetSet()->getFacet('subjects')->getValues());
    $response['facets']['vendors'] = $this->facetFilterList($results->getFacetSet()->getFacet('vendors')->getValues());
    $response['documents'] = $results->getDocuments();

    return $this->json($response);


  }

  private function parseCurrentFilters($filters,$query_string){
    $field_labels = array('subject' => 'Subjects', 'vendor'=>'Vendors/Packages');
    $filter_list = array();
    foreach($filters as $filter)
    {
      $temp_qs = $query_string;
      $temp_qs['filter'] = array_diff($temp_qs['filter'],array($filter));
      list($field,$label) = explode(":",$filter);
      $filter_list[$field_labels[$field]][] = array('label' => $label, 'value' => $filter, 'query_string' => $temp_qs);
    }

    return $filter_list;
  }

  private function parseQuery($query,$field)
  {
    if($field==='all' || is_null($field))
    {
      return $query;
    }else if($field === 'isbn'){
      return 'isbn:' . $query . ' OR ' . 'isbn13:' . $query;
    }
    else{
      return $field . ":" . $query;
    }
  }

  private function facetFilterList($facetArray)
  {
    return array_filter($facetArray, function($value){ return $value > 0; });
  }
}
