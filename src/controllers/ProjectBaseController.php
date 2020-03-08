<?php 

namespace kamruljpi\admintemplate\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class ProjectBaseController extends Controller
{
    public $modelName = '';
    public $baseView = 'admintemplate::';
    public $listView = 'admin.base.list';
    public $formView;
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
    public $listRoute;
    public $createRoute;
    public $editRoute;
    public $deleteRoute;
    public $statusRoute;
    // public function __construct() {
        // $this->modelName = 'kamruljpi\Role\Http\Model\Role';
        // $this->extraBtns = array(
        //     array(
        //         'routeName' => 'role_permission',
        //         'title' => 'Role Permission',
        //         'class' => 'role_permission_cls',
        //     )
        // );
        // $this->btnLists = array(
        //  array(
        //      'routeName' => 'role_permission',
        //      'title' => 'Role Permission',
        //      'class' => 'role_permission_cls',
        //  )
        // );

        // $this->perRowbtnLists = array(
        //  array(
        //      'routeName' => 'role_permission',
        //      'title' => 'Role Permission',
        //      // 'params' => ['id_role'],
        //      // 'class' => 'btn-danger',
        //  )
        // );
        // $this->tableLists = array(
        //     'id_role' => array(
        //         'title' => 'ID',
        //         'align' => 'center',
        //         'class' => 'fixed-width-xs',
        //     ),
        //     'name' => array(
        //         'title' => 'URL',
        //     ),
        //     'is_active' => array(
        //         'title' => 'Status',
        //         'align' => 'center',
        //         'class' => 'fixed-width-xs',
        //     ),
        // );
    // }
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
        if(empty($this->listRoute)){
            $this->listRoute = $this->modelClassName."_index";
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
    	if(request()->ajax()){
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
    	return view($this->baseView.$this->listView, [
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
    public function getValidation($table = null) {
        return [];
    }
    public function submit(Request $request, $url_id = null) {

        $this->init();

        $this->validate($request, $this->getValidation($this->modelObj->getTable()));

        if(isset($request->{$this->primaryKey}) && !empty($request->{$this->primaryKey}) && ($url_id != null)){
            $modelObj = $this->modelName::find($request->{$this->primaryKey});
        }else{
            $modelObj = new $this->modelName();
        }

        $modelObj->fill($request->all());

        if(isset($request->{$this->primaryKey}) && !empty($request->{$this->primaryKey}) && ($url_id != null)){
            $res = $modelObj->update();
        }else{
            $res = $modelObj->save();
        }

        if($res){
            if($this->isAjax){
                return json_encode([
                        'status'=>200,
                        'msg' => 'success',
                        'data' => $res
                    ]);
            }else{
                return redirect()->route($this->listRoute)->withSuccess("Successfuly created New ".$this->modelClassName.".");
            }
        }else{
            if($this->isAjax){
                return json_encode([
                        'status'=>301,
                        'msg' => 'error',
                        'data' => []
                    ]);
            }else{
                return redirect()->route($this->listRoute)->withErrors("Somethings Went Wrong! Please try Again.");
            }
        }
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
    public function displayFormView($id = null)
    {
        $this->init();
        if(isset($this->formView) && !empty($this->formView)){
            if($id == null){
                return view($this->formView);
            }else{
                return view($this->formView, [
                        'data' => $this->modelName::find($id),
                    ]);
            }
        }else{
            return "formView is not defined.";
        }
    }
    public function create() 
    {
        return $this->displayFormView();
    }
    public function createAction(Request $request) 
    {
        return $this->submit($request);
    }
    public function edit($id) 
    {
        return $this->displayFormView($id);
    }
    public function editAction(Request $request, $id) 
    {
        return $this->submit($request, $id);
    }
}
