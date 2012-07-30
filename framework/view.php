<?php
class View {

	protected $variables = array();
	protected $controller;
	protected $action;

	public function __construct($controller, $action) {
		$this->controller	= $controller;
		$this->action 		= $action;
	}

	public function Set($name, $value) {
		$this->variables[$name] = $value;
	}

  public function Render() {
		if ( array_key_exists('layout', $this->variables) ) {
			$this->Set('layoutContent', $this->RenderContent());
			extract($this->variables);
			ob_start();
			if ( file_exists(ROOT . DS . 'application' . DS . 'view' . DS . 'layouts' . DS . $this->variables['layout'] . '.view.php') ) {
				include(ROOT . DS . 'application' . DS . 'view' . DS . 'layouts' . DS . $this->variables['layout'] . '.view.php');
			}
			return ob_get_clean();			
		} else {
			return $this->RenderContent();
		}
	}
	
	private function RenderContent() {
		extract($this->variables);
		ob_start();
		if ( file_exists(ROOT . DS . 'application' . DS . 'view' . DS . $this->controller . DS . $this->action . '.view.php') ) {
			include(ROOT . DS . 'application' . DS . 'view' . DS . $this->controller . DS . $this->action . '.view.php');
		}
		return ob_get_clean();
    }

}