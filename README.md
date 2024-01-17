## Lumen Struct

## 一、代码结构

## 二、Controller控制层

### 2.1 RequsetBo Request业务对象

> RequsetBo集成了lumen的验证器，通过注入的方式完成对请求参数的自动校验，返回可靠的对象可供直接使用

```
class ActivitiesCreateRequestBo extends BaseRequestBo
{
    public $name;

    public $type;

    public $start_at;

    public $end_at;

    public $items;

    protected function rules()
    {
        return array_merge(parent::rules(), [
            'name' => ['required'],
            'type' => ['required'],
            'start_at' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'end_at' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'items' => ['required']
        ]);
    }

}
```

> Controller里面对应的方法使用注入的方式，注ActivitiesCreateRequestBo对象。这时候会自动校验ActivitiesCreateRequestBo对象的参数。如果校验失败会抛出InvalidParameterException异常。

```
/**
     * 创建活动
     * @param ActivitiesCreateRequestBo $activitiesCreateBo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException
     */
    public function create(ActivitiesCreateRequestBo $activitiesCreateBo, Request $request)
    {
        $activitiesCreateBo->sub = $request->get('sub');
        $activitiesCreateBo->stage = $request->get('stage');
        $activitiesModel = $this->service->create($activitiesCreateBo);
        return new SuccessResponseVo($activitiesModel->toArray());
    }
```

### 2.2 ResponseVo 响应对象

> 根据约定0：成功；1：业务处理失败。一般程序判断错误提；2：请求失败。一般为程序报错。对应如下不同的响应对象

- SuccessResponseVo
- BusinessErrorResponseVo
- SystemErrorResponseVo



## 三、Service业务逻辑层

> Service层主要负责Controller控制层和Repositories数据存取层之间的逻辑处理

## 四、Repositories数据存取层

### 4.1 Model
> lumen原生的Model，里面只定义一下数据表和表之间的关联等基本信息

```
class ActivitiesModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'activities';

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'type',
        'sub',
        'stage'
    ];
}
```

### 4.2 Model VO(model fields value oject)
> model vo 就是把model对应的字段的一些定义抽离出来，单独管理

```
class ActivitiesTypeVo
{
    const SEC_KILL = 1;

    const WHEEL = 2;

    private $type;

    /**
     * ActivitiesTypeVo constructor.
     * @param $type
     */
    public function __construct($type = self::SEC_KILL)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getItems()
    {
        return [
            self::SEC_KILL => '秒杀',
            self::WHEEL => '大转盘',
        ];
    }

    public function isSeckill()
    {
        return $this->type == self::SEC_KILL;
    }

    public function isWheel()
    {
        return $this->type == self::WHEEL;
    }
}
```

### 4.3 Repository
> 所有Repository必须继承BaseRepository，BaseRepository基本上就是对Eloquent\Builder的一个封装。
```
public function lists($column, $key = null);

public function pluck($column, $key = null);

public function all($columns = ['*']);

public function paginate($limit = null, $columns = ['*']);

public function simplePaginate($limit = null, $columns = ['*']);

public function find($id, $columns = ['*']);

public function findByField($field, $value, $columns = ['*']);

public function findWhere(array $where, $columns = ['*']);

public function findWhereIn($field, array $values, $columns = ['*']);

public function findWhereNotIn($field, array $values, $columns = ['*']);

public function findWhereBetween($field, array $values, $columns = ['*']);

public function create(array $attributes);

public function update(array $attributes, $id);

public function updateOrCreate(array $attributes, array $values = []);

public function delete($id);

public function orderBy($column, $direction = 'asc');

public function with($relations);

public function whereHas($relation, $closure);

public function withCount($relations);

public function firstOrNew(array $attributes = []);

public function firstOrCreate(array $attributes = []);
```

### 4.4 Criteria查询组装
> 对于CRUD来说，其最难的是R操作，因为会有各种各样的查询方式。然后默认的实现是BaseRepository。但是一些更复杂的查询BaseRepository就显得不够用了，所以我们引入Criteria类。用于把各种查询条件组合到一起
    
```
class SeckillCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->byType(ActivitiesTypeVo::SEC_KILL);
        return $query;
    }

}
```
> 然后在controller中，我们通过下面的方式查询

```
public function index()
{
  $this->repository->pushCriteria(new SeckillCriteria());
  $seckills = $this->repository->all();
  ...
}
```

### 4.5 Query查询定义
> Query主要用于把常用的查询定义公用的方法，实现代码的重用和方便Criteria中查询的组装。首先在Model里面定制Query类

```
class ActivitiesModel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'activities';

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'type',
        'sub',
        'stage'
    ];

    protected function getQuery() {
        return ActivitiesQuery::class;
    }

}

```

> 然后新建自定义的Query类。里面把常用的查询条件方法定义好

```
class ActivitiesQuery extends BaseQuery
{
    /**
     * 根据ID查询
     * @param $id
     * @return ActivitiesQuery
     */
    public function getById($id)
    {
        return $this->where('id', $id);
    }


    /**
     * 根据类型查询
     * @param $type
     * @return ActivitiesQuery
     */
    public function byType($type)
    {
        return $this->where('type', $type);
    }
}

```
  
### 4.6 更新操作
>   更新操作直接调用BaseRepository里面对应的方法

```
public function index()
{
  $this->repository->updateOrCreate(['name' => '更新测试'], ['created_at']]);
  ...
}
```

## 五、日志采集

- 使用阿里云日志扩展lumen-sls
    - 使用方法详见github说明https://github.com/hollisho/lumen-sls

- 异常日志
    - 修改App\Exceptions\Handler中report方法，处理全局的异常，并且使用lumen-sls阿里日志组件上报异常日志到指定日志库xbull_exception_log
```
public function report(Exception $exception)
{
    parent::report($exception);

    /* @var \Illuminate\Http\Request  $request */
    $request = app('request');
    /* @var $slsLog \hollisho\lumensls\SLSLogManager */
    $slsLog = app('sls');
    $slsLog->setLogStore('xbull_exception_log')->putLogs([
        'datetime' => date('Y-m-d H:i:s'),
        'env' => trim(env('APP_ENV')),
        'route' => json_encode(app('request')->route()),
        'request' => json_encode($request->toArray()),
        'header' => json_encode($request->header()),
        'code' => $exception->getCode(),
        'message' => $exception->getMessage(),
        'trace' => $exception->getTraceAsString(),
        'uri' => $request->getRequestUri()
    ]);
}
```

- 业务日志
    - 直接使用lumen提供的Log组件即可上报日志到阿里云

```
\Illuminate\Support\Facades\Log::info('Test Message', ['myname'=>'hollis']);
```
