<?php 

namespace kamruljpi\admintemplate\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
	public $templateData;
	public $withJoin = [];
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
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
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
		$this->setTemplateData();
    	if(empty($this->fillableLists)){
    		$this->fillableLists = Schema::getColumnListing($this->modelObj->getTable());
    		$this->isFillable = false;
    	}
    	if(empty($this->createRoute)){
    		$this->createRoute = $this->modelClassName.".create";
    	}
        if(empty($this->listRoute)){
            $this->listRoute = $this->modelClassName.".index";
        }
    	if(empty($this->editRoute)){
    		$this->editRoute = $this->modelClassName.".edit";
    	}
    	if(empty($this->deleteRoute)){
    		$this->deleteRoute = $this->modelClassName.".delete";
    	}
    	if(empty($this->statusRoute)){
    		$this->statusRoute = $this->modelClassName.".status";
    	}
    	if(request()->ajax() || request()->wantsJson() || $this->startsWith(request()->path(), 'api')){
            $this->isAjax = true;
        }
    	if(empty($this->postPerPage)){
    		$this->postPerPage = 20;
    	}
    	if(empty($this->pageTitle)){
    		$this->pageTitle = config('app.name');
    	}
		if(isset($this->modelObj->withJoin) && count($this->modelObj->withJoin) > 0) {
            $this->withJoin = $this->modelObj->withJoin;
        }
    }
    public function index($paginate = true){
    	$this->init();
        $dataLists = $this->modelObj;
        if(isset($this->withJoin) && count($this->withJoin) > 0) {
            foreach($this->withJoin as $wj) {
                $dataLists = $dataLists->with($wj);
            }
            if($paginate) {
                if(method_exists($this, "pre{$this->modelClassName}list")){
                    $dataLists = $this->{"pre{$this->modelClassName}list"}(['request' => $_GET, 'modelObj'=>$dataLists]);
                }
                $dataLists = $dataLists->paginate($this->postPerPage);
            }else{
                if(method_exists($this, "pre{$this->modelClassName}list")){
                    $dataLists = $this->{"pre{$this->modelClassName}list"}(['request' => $_GET, 'modelObj'=>$dataLists]);
                }
                $dataLists = $dataLists->get();
            }
        }else{
            if($paginate) {
                if(method_exists($this, "pre{$this->modelClassName}list")){
                    $dataLists = $this->{"pre{$this->modelClassName}list"}(['request' => $_GET, 'modelObj'=>$dataLists]);
                }
                $dataLists = $dataLists->paginate($this->postPerPage);
            }else{
                if(method_exists($this, "pre{$this->modelClassName}list")){
                    $dataLists = $this->{"pre{$this->modelClassName}list"}(['request' => $_GET, 'modelObj'=>$dataLists]);
                }
                $dataLists = $dataLists->get();
            }
        }
        if($this->isAjax){
            if(isset($dataLists) && !empty($dataLists)){
                return $this->getApiResponse(200, $dataLists, 'success');
            }else{
                return $this->getApiResponse(301, [], 'error');
            }
        }else{
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
                'listRoute' => $this->listRoute,
                'editRoute' => $this->editRoute,
                'deleteRoute' => $this->deleteRoute,
                'statusRoute' => $this->statusRoute,
                'dataTable' => $this->dataTable,
                'modelClassName' => $this->modelClassName,
                'details' => $dataLists,
            ]);
        }
    }
    public function getValidation($table = null) {
        return [];
    }
    public function submit(Request $request, $url_id = null) {
        $this->init();
        if($this->isAjax){
            $validator = Validator::make($request->all(), $this->getValidation($this->modelObj->getTable()));
            if ($validator->fails()) {
                return $this->getApiResponse(301, $validator->errors(), 'error');
            }
        }else{
            $this->validate($request, $this->getValidation($this->modelObj->getTable()));
        }
        if(isset($request->{$this->primaryKey}) && !empty($request->{$this->primaryKey}) && ($url_id != null)){
            $modelObj = $this->modelName::find($request->{$this->primaryKey});
        }else{
            $modelObj = new $this->modelName();
        }
        $modelObj->fill($request->all());
        if(isset($request->files) && count($request->files) > 0){
            foreach ($request->files as $key => $uploadedFile) {
                $filename = time().$uploadedFile->getClientOriginalName();
                if($uploadedFile->move(public_path('image'), $filename)){
                    if(isset($modelObj->{$key})) {
                        $modelObj->{$key} = $filename;
                    }
                }else{
                    return $this->getApiResponse(301, ["{$key}"=>"Upload Failed"], 'error');
                }
            }
        }

        if(isset($request->{$this->primaryKey}) && !empty($request->{$this->primaryKey}) && ($url_id != null)){
            if(method_exists($this, "pre{$this->modelClassName}update")){
                $modelObj = $this->{"pre{$this->modelClassName}update"}(['request' => $request, 'modelObj'=>$modelObj]);
            }
            $res = $modelObj->update();
            $this->{"post{$this->modelClassName}update"}(['request' => $request, 'modelObj'=>$modelObj]);
        }else{
            if(method_exists($this, "pre{$this->modelClassName}save")){
                $modelObj = $this->{"pre{$this->modelClassName}save"}(['request' => $request, 'modelObj'=>$modelObj]);
            }
            $res = $modelObj->save();
            $this->{"post{$this->modelClassName}save"}(['request' => $request, 'modelObj'=>$modelObj]);
        }

        if($res){
            if($this->isAjax){
                return $this->getApiResponse(200, $res, 'success');
            }else{
                return redirect()->route($this->listRoute)->withSuccess("Successfuly created New ".$this->modelClassName.".");
            }
        }else{
            if($this->isAjax){
                return $this->getApiResponse(301, [], 'error');
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
                    return $this->getApiResponse(200, $modelObj, 'success');
    			}else{
		    		return $this->getApiResponse(301, [], 'error');
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
    		return $this->getApiResponse(301, [], 'error');
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
                    return $this->getApiResponse(200, [], 'success');
    			}else{
                    return $this->getApiResponse(301, [], 'error');
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
            return $this->getApiResponse(301, [], 'error');
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
				$Tempdata = $this->mergeTemplateData([
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
                'listRoute' => $this->listRoute,
                'editRoute' => $this->editRoute,
                'deleteRoute' => $this->deleteRoute,
                'statusRoute' => $this->statusRoute,
                'dataTable' => $this->dataTable,
                'modelClassName' => $this->modelClassName,
            ]);
                return view($this->formView, $Tempdata);
            }else{
				$Tempdata = $this->mergeTemplateData([
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
                    'listRoute' => $this->listRoute,
                    'editRoute' => $this->editRoute,
                    'deleteRoute' => $this->deleteRoute,
                    'statusRoute' => $this->statusRoute,
                    'dataTable' => $this->dataTable,
                    'modelClassName' => $this->modelClassName,
                    'data' => $this->modelName::find($id),
                ]);
                return view($this->formView, $Tempdata);
            }
        }else{
            return "formView is not defined.";
        }
    }
	public function show($id)
    {
        $this->init();
        if(isset($this->withJoin) && count($this->withJoin) > 0) {
            $getData = $this->modelObj;
            foreach($this->withJoin as $wj) {
                $getData = $getData->with($wj);
            }
            $getData = $getData->find($id);
        }else{
            $getData = $this->modelName::find($id);
        }
        if($this->isAjax){
            if(isset($getData) && !empty($getData)){
                return $this->getApiResponse(200, $getData, 'success');
            }else{
                return $this->getApiResponse(301, [], 'error');
            }
        }else{
            if(isset($getData) && !empty($getData)){
                return view($this->formView, [
                        'data' => $getData,
                    ]);
            }else{
                return view($this->formView);
            }
        }
    }
	public function mergeTemplateData($intdata = []) 
    {
		$def = [];
		$Extradef = [];
        if (isset($intdata) && !empty($intdata)){
			$def = $intdata;
		}
		if (isset($this->templateData) && !empty($this->templateData)){
			$Extradef = $this->templateData;
		}
		$res = array_merge($def, $Extradef);
		return $res;
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
	public function setTemplateData(){
		$this->templateData = [];
	}
    public function getApiResponse($code = 200, $data = [], $msg = "success"){
        return json_encode([
            'status'=> $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
    public function __call($methodName, $args)
    {
        if(method_exists($this, $methodName)){
            call_user_func_array($methodName, $args);
        }
    }
}
