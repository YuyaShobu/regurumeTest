    class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
    {
   		 protected function _initView() {
			$options = new Zend_Config($this->getOptions());
			$view_config = $options->view->toArray();
			$smarty_config = $options->view->smarty->toArray();

			require_once eMyLibrary/Smarty.phpf;
			$view = new Zend_View_Smarty($view_config['scriptPath'], $smarty_config);

			$viewRenderer =
			Zend_Controller_Action_HelperBroker::getStaticHelper(gViewRendererh);
			$viewRenderer->setView($view)
			->setViewBasePathSpec($view->_smarty->template_dir)
			->setViewScriptPathSpec(e:controller/:action.:suffixf)
			->setViewScriptPathNoControllerSpec(e:action.:suffixf)
			->setViewSuffix(etplf);
		}
    }