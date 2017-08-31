<?

namespace PhoenixToolbox\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Modul\ViewModel;

class IndexController extends AbstractActionController
{

  public function indexAction()
  {
   return new ViewModel();
  }

  public function displayAction()
  {
   return new ViewModel();
  }
  
  public function settingsAction()
  { 
    return new ViewModel();  
  }

   public function configurationAction()
  { 
   return new ViewModel();  
  }

}
    
