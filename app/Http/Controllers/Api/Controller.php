<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use Helpers;
    protected $model;
    protected $resourceKey;
    protected $transformer;
    protected $transformerInstance;
    protected $only;
    protected $update_only;
    protected $renameKeys;
    public function index()
    {
        $query = $this->getModel()->query();
        if ($limit = request()->limit) {
            $models = $query->paginate($limit);
            return $this->paginator($models);
        }
        $models = $query->get();
        return $this->collection($models);
    }
    public function store()
    {
        $payload = $this->getPayload();
        $model = $this->getModel()->create($payload);
        return $this->item($model);
    }
    public function show($id)
    {
        $model = $this->getModel()->find($id);
        if (!$model) {
            return $this->errorNotFound();
        }
        return $this->item($model);
    }
    public function update($id)
    {
        $model = $this->getModel()->find($id);
        if (!$model) {
            return $this->errorNotFound();
        }
        $payload = $this->getUpdatePayload();
        $model->update($payload);
        return $this->item($model);
    }
    public function destroy($id)
    {
        $model = $this->getModel()->find($id);
        if (!$model) {
            return $this->errorNotFound();
        }
        $model->delete();
        return $this->item($model);
    }
    protected function item($model, TransformerAbstract $transformer = null, $resource_key = null)
    {
        if (!$transformer) {
            $transformer = $this->getTransformer();
        }
        return $this->response->item($model, $transformer, ['key' => $resource_key ?: $this->getResourceKey()]);
    }
    protected function collection($models, TransformerAbstract $transformer = null, $resource_key = null)
    {
        if (!$transformer) {
            $transformer = $this->getTransformer();
        }
        return $this->response->collection($models, $transformer, ['key' => $resource_key ?: $this->getResourceKey()]);
    }
    protected function paginator($models, TransformerAbstract $transformer = null, $resource_key = null)
    {
        if (!$transformer) {
            $transformer = $this->getTransformer();
        }
        return $this->response->paginator($models, $transformer, ['key' => $resource_key ?: $this->getResourceKey()]);
    }
    protected function errorNotFound($message = null)
    {
        $message = $message ?: "{$this->getHumanizeResourceName()} not found.";
        return $this->response->errorNotFound($message);
    }
    protected function errorUnauthorized($message = null)
    {
        return $this->response->errorUnauthorized($message);
    }
    protected function getHumanizeResourceName()
    {
        $name = str_replace('_', ' ', snake_case($this->getResourceName()));
        return ucfirst($name);
    }
    protected function getModel()
    {
        return app($this->model);
    }
    protected function getModelName()
    {
        return class_basename($this->model);
    }
    protected function getPayload()
    {
        $payload = $this->only ? request()->only($this->only) : request()->input();
        if ($this->renameKeys) {
            return array_rename_keys($payload, $this->renameKeys);
        }
        return $payload;
    }
    protected function getUpdatePayload()
    {
        if ($this->update_only) {
            $payload = request()->only($this->update_only);
        } else {
            $payload = $this->only ? request()->only($this->only) : request()->input();
        }
        if ($this->renameKeys) {
            return array_rename_keys($payload, $this->renameKeys);
        }
        return $payload;
    }
    protected function getResourceKey()
    {
        return str_plural($this->resourceKey ?: $this->getResourceName());
    }
    protected function getResourceName()
    {
        if (!$this->resourceKey) {
            $resource_key = get_class($this->getTransformer()) . "::RESOURCE_KEY";
            $this->resourceKey = defined($resource_key) ? constant($resource_key) : camel_case($this->getModelName());
        }
        return str_singular($this->resourceKey);
    }
    protected function getTransformer()
    {
        if ($this->transformerInstance) {
            return $this->transformerInstance;
        }
        if ($this->transformer) {
            $this->transformerInstance = app($this->transformer);
        } else {
            $modelName = $this->getModelName();
            $this->transformerInstance = app("\\App\\Transformers\\{$modelName}Transformer");
        }
        return $this->getTransformer();
    }
}
