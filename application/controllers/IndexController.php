<?php

use Icinga\Web\Controller\ActionController;
use Icinga\Web\Url;

class Graphite_IndexController extends ActionController
{
    protected $grapher;

    public function init()
    {
        $this->grapher = new \Icinga\Module\Graphite\Grapher;
    }

    public function indexAction()
    {
        $this->view->url = $this->getParam('graphite_url');
        $this->view->iframe_w = $this->getParam('graphite_iframe_w');
        $this->view->iframe_h = $this->getParam('graphite_iframe_h');
        if ($this->grapher->getRemoteFetch()) {
            $this->view->url = $this->_request->getScheme()."://".
                               $this->_request->getHttpHost().
                               Url::fromPath('graphite/index/graph', array(
                                   'target' => $this->getParam('graphite_url')
                               ));
        } else {
            $this->view->url = urldecode($this->getParam('graphite_url'));
        }
    }

    public function graphAction()
    {
        $this->_helper->layout()->disableLayout();
        $target = $this->getParam('target');
        $from = $this->getParam('from');
        $xFormat = $this->getParam('xFormat');
        $largeImgUrl = $this->grapher->getLargeImgUrl($target, $from, $xFormat);

        if ($this->grapher->getRemoteFetch()) {
            $largeImgUrl = $this->grapher->inlineImage($largeImgUrl);
        }

        $this->view->largeImgUrl = $largeImgUrl;
        $this->view->target = $target;
    }
}
