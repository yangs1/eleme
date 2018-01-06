<?php

namespace Foundation\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;

class FromRequests //extends Request
{
    use ValidatesWhenResolvedTrait;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    protected $scene;
    /**
     * Prepare the data for validation.
     * @param $scene
     * @return self
     */
    public function scene( $scene )
    {
        $this->scene = $scene;

        return $this;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorInstance( array $data, $rules )
    {
        $factory = app( Factory::class );

        $instance =  $factory->make(
            $data, $rules, $this->messages(), $this->attributes()
        );

        if (method_exists($this, 'validator')) {
            return app()->call([$this, 'validator'], compact('instance', 'rules'));
        }

        return $instance;
    }

    /**
     * Validate the class instance.
     * @param \Illuminate\Http\Request
     * @return void
     */
    public function validate( Request $request )
    {
        $this->prepareForValidation();

        $rules = $this->scene ?
            array_merge($this->rules(), $this->sceneRules()[$this->scene] ?? []) : $this->rules();

        $instance = $this->getValidatorInstance( $request->all(), $rules );

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        } elseif (! $instance->passes()) {
            $this->failedValidation($instance);
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException( new JsonResponse( $this->formatErrors($validator), 422));
    }
    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        return ['code'=>422, 'result'=>$validator->getMessageBag()->toArray(), 'message'=>trans('validation.error_message')];
        //return $validator->getMessageBag()->toArray();
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    public function sceneRules()
    {
        return [];
    }

    public function rules()
    {
        return [];
    }

}
