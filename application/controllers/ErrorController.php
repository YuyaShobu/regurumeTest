<?php

/**
 * デフォルトエラーコントローラーアクション
 * このアクションでは以下のエラーを扱います。
 *	- アプリケーションエラー全般
 *	- 存在しないコントローラー／メソッドが呼び出されたときの404エラー
 *  - 上記以外のエラー
 */
require_once "Zend/Debug.php";
#require_once (LIB_PATH .'/managerLogger.php');


class ErrorController extends Zend_Controller_Action
{


    /**
     * エラーアクション
     */
	public function errorAction()
	{
		if (null !== $this->view and get_class($this->view) != 'Zend_View') {
			$view = new Zend_View_Smarty(
			APPLICATION_PATH . '/views/scripts', array(
			        'compile_dir' => APPLICATION_PATH . '/views/templates_c',
			        'config_dir'  => APPLICATION_PATH . '/views/configs',
			        'cache_dir'   => APPLICATION_PATH . '/views/cache',
			        'left_delimiter'   => '{{',
			        'right_delimiter'  => '}}'
			        )
			);
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
			$viewRenderer->setView($view)
			->setViewBasePathSpec($view->_smarty->template_dir)
			->setViewScriptPathSpec(':controller/:action.:suffix')
			->setViewScriptPathNoControllerSpec(':action.:suffix')
			->setViewSuffix('html');

			//$view = new Zend_View();
			//$this->view = null;
			//$viewRenderer = $this->getHelper('ViewRenderer');
			//$viewRenderer->setView($view)
			//->setViewSuffix('html')
			//->initView();
		}

		$errors = $this->_getParam('error_handler');
        $this->outErrorLog($errors->exception);
		switch ($errors->type)
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
				$title = 'HTTP/1.1 404 Not Found';
				$this->view->windowTitle = $title;
				$this->view->pageTitle = $title;
				break;
			default:
				$title = 'Application Error';
				$this->view->windowTitle = $title;
				$this->view->pageTitle = $title;
				break;
		}

        //$this->_helper->viewRenderer('commonerror');
		//$this->view->errorMessage = $errors->exception;
		//$this->view->errorInfo = Zend_Debug::dump($errors);

	}

    /**
     * エラーログ出力
     *
     * @param Exception $e 例外
     */
    private function outErrorLog(&$e){
        //*******************************************************
        // 例外ハンドリング(画面にエラー詳細表示)
        //*******************************************************
        /*if(!is_null($e)){
            $logger = managerLogger::getInstance();

            //例外のメッセージを取得
            $errorMessage = $e->getMessage();
            //例外のスタックトレースを文字列で返します。
            $errorTrace = $e->getTraceAsString();
            //*******************************************************
            // ログ出力
            //*******************************************************
            //Zend_Controller_Dispatcher_Exceptionは404エラー時発生
            $logger->err($errorMessage . "\n" .  $errorTrace);
        }*/
    }
}
