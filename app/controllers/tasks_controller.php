<?php

class TasksController extends AppController {

  var $name = 'Tasks';
  var $helpers = array('Ajax', 'Js', 'Session');
  var $components = array('RequestHandler');
  
  function beforeFilter() {
    $this->Auth->allowedActions = array('index', 'view', 'add', 'edit', 'delete');
    parent::beforeFilter();
  }
  
  function index() {
    $this->Task->recursive = 0;
    $json = $this->Task->find('all', array('fields' => array('id', 'done', 'name', 'order'), 'order' => array('order')));
    $this->set('json', $json);
    $this->render(SIMPLE_JSON, 'ajax');
  }

  function view($id = null) {
    if (!$id) {
      $this->Session->setFlash(__('Invalid todo', true));
      $this->redirect(array('action' => 'index'));
    }
    $this->set('json', $this->Task->read(null, $id));
    $this->render(SIMPLE_JSON, 'ajax');
  }

  function add() {
    // validate the record to make sure we have all the data
    if (empty($this->data['Task']['name'])) {
      // we got bad data so set up an error response and exit
      header('HTTP/1.1 400 Bad Request');
      header('X-Reason: Received an array of records when ' .
              'expecting just one');
      exit;
    }

    $this->Task->create();
    $this->Task->save($this->data);
    $this->set('json', $this->data['Task']);
    $this->render(SIMPLE_JSON, 'ajax');
  }

  function edit($id = null) {

    if (empty($id)) {
      return;
    }

    if ($this->Task->save($this->data)) {
      $this->set('json', $this->data['Task']);
      $this->render(SIMPLE_JSON, 'ajax');
    }
  }

  function delete($id = null) {

    if (!$id) {
      exit;
    }
    $this->Task->delete($id);
  }

}

?>