<?php

namespace hollis1024\LumenStruct\Bo;


use Illuminate\Http\Request;

class BaseRequestBo extends BaseBo
{
    private $request;

    private $autoLoad = true;

    private $autoValidate = true;

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
            $this->autoValidate && $this->validate(true);
            $this->request = $request;
        }
    }

    public function getRequest()
    {
        return $this->request;
    }

}