<?php

namespace CoreBundle;


use CoreBundle\Entity\File;
use CoreBundle\Entity\FriendlyUrl;
use CoreBundle\Entity\NodeTerm;
use CoreBundle\Entity\PageMeta;
use CoreBundle\Entity\SystemConfig;
use CoreBundle\Utility\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Yaml\Yaml;

class CoreCommonController extends Controller
{
    public function _getRootDir()
    {
        $rootDir = $this->get('kernel')->getRootDir();
        $rootDir = rtrim($rootDir, '\app');
        $rootDir = rtrim($rootDir, '/app');

        return $rootDir;
    }

    public function _uploadFile($fileObj, $path = '')
    {
        $file = new File();
        $file->upload($this->_getRootDir(), $fileObj);
        $file->setCreatedBy(time());
        $file->setStatus(1);
        $file = $this->_saveEntity($file);

        return $file->getId();
    }

    public function _loadFileById($fileId)
    {
        $currentImageFile = $this->_getEntityByID('CoreBundle:File', $fileId);
        if ($currentImageFile) {
            return $currentImageFile;
        } else {
            return null;
        }
    }

    public function _deleteFile($fileId)
    {
        $entity = $this->_loadFileById($fileId);
        if ($entity) {
            $filePath = $entity->getFilePath();
            if (file_exists($this->_getRootDir().'/'.$entity->getFilesDir().'/'.$filePath)) {
                unlink($this->_getRootDir().'/'.$entity->getFilesDir().'/'.$filePath);
            }
            //unlink($this->_getRootDir().'/'.$entity->getThumbsDir().'/'.$entity->getId().'/*.*');
            unlink($this->_getRootDir().'/'.$entity->getThumbsDir().'/'.$entity->getId());

            return true;
        } else {
            return false;
        }
    }

    public function _savePageMeta($pageId, $metaCode, $metaValue)
    {
        $pageMetaEntity = $this->_getEntityByConditions(
          'CoreBundle:PageMeta',
          array(
            'pageId' => $pageId,
            'metaCode' => $metaCode,
          )
        );
        if ($pageMetaEntity) {

        } else {
            $pageMetaEntity = new PageMeta();
            $pageMetaEntity->setPageId($pageId);
            $pageMetaEntity->setMetaCode($metaCode);
        }
        $pageMetaEntity->setMetaValue($metaValue);
        $pageMetaEntity = $this->_saveEntity($pageMetaEntity);

        return $pageMetaEntity;
    }

    public function _getPageMetas($pageId)
    {
        $dql = "SELECT pm.metaCode, pm.metaValue
            FROM CoreBundle:PageMeta pm
            WHERE pm.pageId = :pageId";
        $arr = $this->_executeDQL($dql, array('pageId' => $pageId));
        $variables = array();
        foreach ($arr as $value) {
            $variables[$value['metaCode']] = $value['metaValue'];
        }

        return $variables;
    }

    public function _updateNodeTerms($nodeId, $termIds = array())
    {


        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $q = $qb->update('CoreBundle:NodeTerm', 'nt')
          ->set('nt.status', $qb->expr()->literal(0))
          ->where('nt.nodeId = :nodeId')
          ->setParameter('nodeId', $nodeId)
          ->getQuery();
        $p = $q->execute();


        foreach ($termIds as $termId) {
            $nodeTermEntity = $this->_getEntityByConditions(
              'CoreBundle:NodeTerm',
              array(
                'nodeId' => $nodeId,
                'termId' => $termId,
              )
            );
            if ($nodeTermEntity) {

            } else {
                $nodeTermEntity = new NodeTerm();
                $nodeTermEntity->setNodeId($nodeId);
                $nodeTermEntity->setTermId($termId);

            }
            $nodeTermEntity->setStatus(1);
            $nodeTermEntity = $this->_saveEntity($nodeTermEntity);
        }

        return true;
    }

    public function _getSelectedTermIdsByNode($nodeId)
    {
        $arr = $this->_executeDQL(
          "SELECT nt.termId 
          FROM CoreBundle:NodeTerm nt 
          WHERE nt.nodeId = :nodeId
          AND nt.status = 1
          ORDER BY nt.id",
          array('nodeId' => $nodeId)
        );
        $options = array();
        foreach ($arr as $value) {
            $options[] = $value['termId'];
        }

        return $options;
    }

    public function _getAllTermOptionsByTaxonomyCode($taxonomyCode)
    {
        $arr = $this->_executeDQL(
          "SELECT t.id, t.name 
          FROM CoreBundle:Term t 
          WHERE t.taxonomyCode = :taxonomyCode
          ORDER BY t.weight ASC, t.id ASC",
          array('taxonomyCode' => $taxonomyCode)
        );
        $options = array();
        foreach ($arr as $value) {
            $options[$value['id']] = $value['name'];
        }

        return $options;
    }

    public function _executeSQL($sql, $params)
    {
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();

        return $statement->fetchAll();
    }

    public function _url($url)
    {

    }

    public function _updateFriendlyUrl($source, $alias, $langcode = '')
    {
        $currentFriendlyUrlEntity = $this->_getEntityByConditions('CoreBundle:FriendlyUrl', array('source' => $source));
        if ($currentFriendlyUrlEntity) {

        } else {
            $currentFriendlyUrlEntity = new FriendlyUrl();
            $currentFriendlyUrlEntity->setSource($source);
        }
        $currentFriendlyUrlEntity->setAlias($alias);
        $currentFriendlyUrlEntity->setLangcode($langcode);

        $currentFriendlyUrlEntity = $this->_saveEntity($currentFriendlyUrlEntity);

        return $currentFriendlyUrlEntity->getId();
    }

    function _getSymfonyUrl($path)
    {
        try {
            $router = $this->get('router');

            $arr = $router->match($path);
            $_route = $arr['_route'];
            $_controller = $arr['_controller'];

            $arrParams = array();
            foreach ($arr as $key0 => $value0) {
                if ($key0 != '_controller' && $key0 != '_route') {
                    $arrParams[$key0] = $value0;
                }
            }
            $pathUrl = $this->generateUrl($_route, $arrParams);
        } catch (\Exception $e) {
            $pathUrl = $path;
        }

        return $pathUrl;
    }

    function _initVariables()
    {
        global $variables;
        if (!is_array($variables)) {
            $dql = 'SELECT sc.name,sc.value
            FROM CoreBundle:SystemConfig sc';
            $arr = $this->_executeDQL($dql, array());
            $variables = array();
            foreach ($arr as $value) {
                $variables[$value['name']] = $value['value'];
            }
        }

        return $variables;
    }

    function _variableGet($key)
    {
        $variables = $this->_initVariables();
        if (isset($variables[$key])) {
            return $variables[$key];
        } else {
            return '';
        }
    }

    function _variableSet($key, $value)
    {
        if ($currentSetting = $this->_getEntityByConditions(
          'CoreBundle:SystemConfig',
          array('name' => $key)
        )
        ) {
            $currentSetting->setValue($value);
            $currentSetting = $this->_saveEntity($currentSetting);
        } else {
            $newSetting = new SystemConfig();
            $newSetting->setName($key);
            $newSetting->setValue($value);
            $newSetting = $this->_saveEntity($newSetting);
        }
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    public function addFlash($type, $message)
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }

        $this->container->get('session')->getFlashBag()->add($type, $message);
    }

    public function _getPager($currentPage, $totalPages, $pageName, $queries)
    {
        return Pager::getPager($this, $currentPage, $totalPages, $pageName, $queries);
    }

    function _getReservationTime(
      $reservation,
      $default_from = 0,
      $default_to = 0
    ) {
        $data = new \stdClass();
        $data->reservation = '';

        $data->from = $default_from;
        $data->to = $default_to;

        $arr = explode(' - ', $reservation);
        if (count($arr) == 2) {
            $from_int = $this->_convertDateToInt($arr[0]);
            $to_int = $this->_convertDateToInt($arr[1]) + 1 * 24 * 60 * 60;
            $data->reservation = date('m/d/Y', $from_int).' - '.date(
                'm/d/Y',
                $from_int
              );
            $data->from = $from_int;
            $data->to = $to_int;
        } else {
            //do nothing
        }


        return $data;
    }

    function _convertDateToInt($str_date)
    {
        $str_date = trim($str_date);
        $arr = explode('/', $str_date);
        if (count($arr) == 3) {
            $month = $arr[0];
            $day = $arr[1];
            $year = $arr[2];
        } else {
            $month = date('m');
            $day = date('d');
            $year = date('Y');
        }

        return mktime(0, 0, 0, $month, $day, $year);
    }

    public function getContainerParameter($key)
    {
        try {
            return $this->container->getParameter($key);

        } catch (\Exception $e) {
            return null;
        }
    }

    public function _getYamlValueFromFile($path, $default = array())
    {
        try {
            return Yaml::parse(file_get_contents($path));
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function _getConfigValue($paramId)
    {
        return $this->container->getParameter($paramId);
    }

    public function _getRootPath()
    {
        return $this->getRequest()->server->get('DOCUMENT_ROOT').$this->getRequest()->server->get('BASE');
    }

    public function _getArrayValue($arr, $key, $default = false)
    {
        if (isset($arr) && is_array($arr) && isset($arr[$key])) {
            return $arr[$key];
        } else {
            return $default;
        }
    }

    public function _addJs($type, $functionKey, $filePath)
    {
        global $scripts;
        $scripts[$type][$functionKey] = $filePath;
    }

    public function _addCss($filePath, $type)
    {
        global $styles;
        $styles[$filePath] = $type;
    }

    public function _createLink($title, $path)
    {
        $request = $this->getRequest();
        $current = explode('?', $request->getRequestUri())[0];

        return array(
          'title' => $title,
          'href' => $path,
          'active' => $current == $path ? true : false,
        );
    }

    public function _getPageTitle()
    {
        global $page_title;
        if ($page_title) {
            return $page_title;
        }
    }

    public function _setPageTitle($pageTitle)
    {
        global $page_title;
        $page_title = $pageTitle;
    }

    //Meta Tags
    public function _getMetaTags()
    {
        global $meta_tags;
        if ($meta_tags) {
            return $meta_tags;
        } else {
            return array();
        }
    }

    public function _setMetaTags($metaTags)
    {
        global $meta_tags;
        $meta_tags = $metaTags;
    }

    public function _addMetaTags($key, $value)
    {
        global $meta_tags;
        if (!$meta_tags) {
            $meta_tags = array();
        }
        $meta_tags[$key] = $value;
    }


    public function getContainer($key)
    {
        return $this->container->get($key);
    }

    public function encodePassword($email, $password)
    {
        return \CoreBundle\Utility\SitePassword::encodePassword($this, $email, $password);
    }

    public function encodeAdminPassword($email, $password)
    {
        return \CoreBundle\Utility\SitePassword::encodeAdminPassword($this, $email, $password);
    }

    /* DB Queries */
    public function _deleteEntityObj($entityObj)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($entityObj);
        $em->flush();
    }

    public function _deleteEntityByID($entity, $id)
    {
        $obj = $this->_getEntityByID($entity, $id);
        $this->_deleteEntityObj($obj);
    }

    public function _getEntityByID($entity, $id)
    {
        return $this->getDoctrine()
          ->getRepository($entity)
          ->find($id);
    }

    public function _getEntityByConditions($entity, $conditions)
    {
        return $this->getDoctrine()
          ->getRepository($entity)
          ->findOneBy(
            $conditions
          );
    }

    public function _saveEntity($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return $entity;
    }


    public function _updateDQL($dql, $params)
    {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query = $query->setParameter($key, $value);
        }

        return $query->execute();
    }

    public function _executeDQL($dql, $params, $limit = 0)
    {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query = $query->setParameter($key, $value);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function _executePagerDQL(
      $dql,
      $params,
      $itemsPerPage = 10,
      $currentPage = 1
    ) {

        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query->setParameter($key, $value);
        }

        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
          ->setMaxResults($itemsPerPage);

        $arr = $query->getResult();

        return $arr;

    }

    /* /DB Queries */

    public function __getSiteConfig()
    {
        //global $webcon
    }


    /** Override functions **/
    public function isGranted($attributes, $object = null)
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attributes, $object);
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $url = $this->container->get('router')->generate($route, $parameters, $referenceType);

        return $url;
    }

    public function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }
}