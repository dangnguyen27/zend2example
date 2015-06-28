<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Hydrator\ClassMethods;

use Blog\Model\Post;
use Blog\Model\PostMapper;
use Blog\Form\PostForm;

class PostController extends AbstractActionController
{
	public function indexAction()
	{
		$postmapper = $this->getServiceLocator()->get('PostMapper');
		$posts = $postmapper->fetchAll();

		return new ViewModel(array('posts'=>$posts));
	}

	public function editAction()
	{
		$id = $this->params('id');
		$post = $this->getServiceLocator()->get('PostMapper')->getPost($id);
		$form = new PostForm();
		$form->setHydrator(new ClassMethods());
		$form->bind($post);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$this->getServiceLocator()->get('PostMapper')->savePost($post);

				return $this->redirect()->toUrl('/post');
			}
		}
		return new ViewModel(array('form'=>$form));
	}

	public function deleteAction()
	{
		$id = $this->params('id');
		$this->getServiceLocator()->get('PostMapper')->deletePost($id);
		return $this->redirect()->toUrl('/post');
	}

	public function newAction()
	{
		$post = new Post();
		$form = new PostForm();
		$form->setHydrator(new ClassMethods());
		$form->bind($post);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$this->getServiceLocator()->get('PostMapper')->savePost($post);

				return $this->redirect()->toUrl('/post');
			}
		}
		return new ViewModel(array('form'=>$form));
	}

	public function viewAction()
	{
		$id = $this->params('id');

		$postmapper = $this->getServiceLocator()->get('PostMapper');
		$post = $postmapper->getPost($id);

		return new ViewModel(array('post'=>$post));
	}
}