<?php 

namespace kamruljpi\admintemplate\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use kamruljpi\Role\Http\Model\UserRoleMenu;
class ProjectBaseController extends Controller
{
    public $modelName = '';
    public $baseView = 'admintemplate::';
    public $indexView = 'admin.base.index';
    public $statusKey = 'is_active';
    public $isAjax = false;
    public $primaryKey;
    public $pageTitle;
    public $tableLists = array();
    public $createBtnShow = true;
    public $postPerPage;
    public $editBtnShow = true;
    public $deleteBtnShow = true;
    public $extraBtns = array();
    public $btnLists = array();
    public $perRowbtnLists = array();
    public $modelObj;
    public $fillableLists;
    public $modelClassName;
    public $dataTable = true;
    public $isFillable = true;
    public $createRoute;
    public $editRoute;
    public $deleteRoute;
    public $statusRoute;
    public function init(){
    	if(empty($this->modelName)){
    		$calledClass = get_called_class();
    		$calledClassExplode = explode("\\", $calledClass);
    		$cClassCount = (count($calledClassExplode) - 1);
    		$controllerClassName = $calledClassExplode[$cClassCount];
    		$this->modelClassName = str_replace("Controller", "", $controllerClassName);
    		$calledClassExplode[($cClassCount - 1)] = "Model";
    		$calledClassExplode[$cClassCount] = $this->modelClassName;
    		$modelClassNameSpace = implode("\\", $calledClassExplode);
    		$this->modelName = $modelClassNameSpace;
    	}
    	$this->modelObj = (new $this->modelName);
    	$this->fillableLists = $this->modelObj->getFillable();
    	$this->primaryKey = $this->modelObj->getKeyName();
    	$models = explode("\\", $this->modelName);
    	$mcount = (count($models) - 1);
    	$this->modelClassName = isset($models[$mcount]) ? strtolower($models[$mcount]) : '';
    	if(empty($this->fillableLists)){
    		$this->fillableLists = Schema::getColumnListing($this->modelObj->getTable());
    		$this->isFillable = false;
    	}
    	if(empty($this->createRoute)){
    		$this->createRoute = $this->modelClassName."_create";
    	}
    	if(empty($this->editRoute)){
    		$this->editRoute = $this->modelClassName."_edit";
    	}
    	if(empty($this->deleteRoute)){
    		$this->deleteRoute = $this->modelClassName."_delete";
    	}
    	if(empty($this->statusRoute)){
    		$this->statusRoute = $this->modelClassName."_status";
    	}
    	if(Request::ajax()){
    		$this->isAjax = true;
    	}
    	if(empty($this->postPerPage)){
    		$this->postPerPage = 20;
    	}
    	if(empty($this->pageTitle)){
    		$this->pageTitle = config('app.name');
    	}
    }
    public function index(){
    	$this->init();
    	$dataLists = $this->modelName::paginate($this->postPerPage);
    	if(isset($dataLists) && !empty($dataLists) && count($dataLists) > 0){
    		foreach ($dataLists as &$value) {
    			if(isset($value->{$this->statusKey})){
    				$value->{$this->statusKey} = $this->statusHtml($value->{$this->primaryKey},$value->{$this->statusKey});
    			}
    		}
    	}
    	return view($this->baseView.$this->indexView, [
			'createBtnShow' => $this->createBtnShow,
			'editBtnShow' => $this->editBtnShow,
			'deleteBtnShow' => $this->deleteBtnShow,
			'tableLists' => $this->tableLists,
			'isFillable' => $this->isFillable,
			'btnLists' => $this->btnLists,
			'pageTitle' => $this->pageTitle,
			'perRowbtnLists' => $this->perRowbtnLists,
			'fillableLists' => $this->fillableLists,
			'primaryKey' => $this->primaryKey,
			'extraBtns' => $this->extraBtns,
			'createRoute' => $this->createRoute,
			'editRoute' => $this->editRoute,
			'deleteRoute' => $this->deleteRoute,
			'statusRoute' => $this->statusRoute,
			'dataTable' => $this->dataTable,
			'modelClassName' => $this->modelClassName,
			'details' => $dataLists,
		]);
    }
    public function Status($id = null){
    	if($id == null){
    		return false;
    	}
    	$this->init();
    	$modelObj = $this->modelName::find($id);
    	if(isset($modelObj->is_active)){
    		$is_active = !($modelObj->is_active);
    		$modelObj->is_active = (int)$is_active;
    		if($this->isAjax){
    			if($modelObj->update()){
    				return json_encode($modelObj);
    			}else{
		    		return json_encode([
			    			'status'=>301,
			    			'msg' => 'error',
			    			'data' => []
		    			]);
    			}
    		}else{
    			if($modelObj->update()){
    				return redirect()->back()->withSuccess("Successfuly updated ".ucwords($this->modelClassName));
    			}else{
    				return redirect()->back()->withError("Somethings Wrong on ".$this->modelClassName);
    			}
    		}
    	}
    	if($this->isAjax){
    		return json_encode([
	    			'status'=>301,
	    			'msg' => 'error',
	    			'data' => []
    			]);
    	}else{
    		return redirect()->back()->withError("Somethings Wrong on ".$this->modelClassName);
    	}
    }
    public function Delete($id = null){
    	if($id == null){
    		return false;
    	}
    	$this->init();
    	$modelObj = $this->modelName::find($id);
    	if(isset($modelObj->{$this->primaryKey})){
    		if($this->isAjax){
    			if($modelObj->delete()){
	    			return json_encode([
				    			'status'=> 200,
				    			'msg' => 'success',
				    			'data' => []
			    			]);
    			}else{
	    			return json_encode([
				    			'status'=> 301,
				    			'msg' => 'error',
				    			'data' => []
			    			]);
    			}
    			
    		}else{
    			if($modelObj->delete()){
    				return redirect()->back()->withSuccess("Successfuly Deleted ".ucwords($this->modelClassName));
    			}else{
    				return redirect()->back()->withError("Somethings Wrong on ".$this->modelClassName);
    			}
    		}
    	}
    	if($this->isAjax){
    		return json_encode([
	    			'status'=> 301,
	    			'msg' => 'error',
	    			'data' => []
    			]);
    	}else{
    		return redirect()->back()->withError("Somethings Wrong on ".$this->modelClassName);
    	}
    }
    public function statusHtml($id = null, $value = 0){
    	if($id == null){
    		return $value;
    	}
    	$link = Route($this->statusRoute, $id);
    	if($value == 1){
    		$html = '<a href="'.$link.'" class="btn btn-success">Enable</a>';
    	}else{
    		$html = '<a href="'.$link.'" class="btn btn-danger">Disable</a>';
    	}
    	return $html;
    }
}
