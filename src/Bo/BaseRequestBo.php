<?php

namespace hollis1024\lumen\struct\Bo;


use Illuminate\Http\Request;

class BaseRequestBo extends BaseBo
{
    private $request;

    /**
     * 自动加载数据
     * @var bool
     */
    protected $autoLoad = true;

    /**
     * 自动验证数据
     * @var bool
     */
    protected $autoValidate = true;

    /**
     * 验证不通过抛异常
     * @var bool
     */
    protected $throwable = true;

    /**
     * BaseRequestBo constructor.
     * @param Request $request
     * @throws \ReflectionException
     */
    public function __construct(Request $request)
    {
        if ($this->autoLoad) {
            if ($request->isJson()) {
                $data = $request->json();
                $data = json_decode($data, true);
            } else if ($request->isMethod('GET')) {
                $data = $request->query();
            } else {
                $data = $request->input();
            }

            $this->load($data);
            $this->autoValidate && $this->validate($this->throwable);
            $this->request = $request;
        }
    }

    public function getRequest()
    {
        return $this->request;
    }

}