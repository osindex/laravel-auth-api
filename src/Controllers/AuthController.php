<?php

namespace Osi\AuthApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Osi\AuthApi\Resources\AuthCollection;

class AuthController extends Controller
{
    /**
     * login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $provider = $request->get('provider', 'admin-api');
        if (!$model = config('auth.providers.' . $provider . '.model')) {
            throw new \RuntimeException('Unable to determine authentication model from configuration.');
        }
        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where('username', $username)->first();
        }
        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
        return $this->data($user->createToken($request->provider)->plainTextToken);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return $user->resourceFormat();
    }
    /**
     * 根据model返回用户数据
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function users(Request $request)
    {
        if ($request->has('depend')) {
            $depends = json_decode($request->get('depend'));
            $provider = $depends->device;
        } else {
            $provider = $request->get('device', 'admin-api');
        }

        if (!$model = config('auth.providers.' . $provider . '.model')) {
            throw new \RuntimeException('Unable to determine authentication model from configuration.');
        }

        if ($request->has('extUrlParams')) {
            $extUrlParams = json_decode($request->get('extUrlParams'));
            $format = $extUrlParams->format;
        } else {
            $format = $request->get('format', 'json');
        }
        if (method_exists($model, 'queryLike')) {
            $query = (new $model)->queryLike($request->get('query'));
        } else {
            $query = (new $model);
        }
        $data = $query->paginate((int) $request->get('per_page', 10));
        switch ($format) {
            case 'options':
                $data->getCollection()->transform(function ($item) {
                    return new JsonResource(['avatar' => $item->avatar, 'label' => $item->name . '-[' . $item->id . ']', 'value' => $item->id]);
                });
                break;
            default:
                break;
        }
        return new AuthCollection($data);

    }
}
