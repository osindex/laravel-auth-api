<?php

namespace Osi\AuthApi\Controllers;

use Osi\AuthApi\Models\ApiPermission;
use SmallRuralDog\Admin\Components\Avatar;
use SmallRuralDog\Admin\Components\Select;
use SmallRuralDog\Admin\Components\Tag;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class PermissionController extends AdminController
{
    protected function showPageHeader()
    {
        return false;
    }
    protected function title()
    {
        return '禁止访问权限';
    }

    protected function grid()
    {
        $grid = new Grid(new ApiPermission());

        $grid->defaultSort('id', 'asc');

        $grid->quickSearch(['slug', 'name']);
        $grid->column('id', 'ID')->sortable()->width('80px');
        $grid->column('model.avatar', '头像', 'model_id')->component(Avatar::make()->size('small'))->width(80);
        $grid->column('model.name', '用户', 'model_id')->help('操作用户')->sortable();
        $grid->column('name', '名称')->width(100)->align('center')->component(Tag::make());

        $grid->column('device', '应用/类型')->align('center')->customValue(function ($row, $value) {
            $getDevicesOptions = getDevicesOptions(false);
            return isset($getDevicesOptions[$value]) ? $getDevicesOptions[$value]['label'] : $value;
        })->component(Tag::make());
        $grid->column('router', '禁止路由')->component(Tag::make());

        $grid->actions(function (Grid\Actions $actions) {
            $actions->hideViewAction();
        });

        return $grid;
    }

    protected function form($edit = false)
    {
        $form = new Form(new ApiPermission());
        $form->item('name', '名称')->required();
        $form->item('device', '应用/类型')
            ->required()
            ->component(function () {
                return Select::make()
                    ->block()
                    ->clearable()
                    ->options(getDevicesOptions());
            });
        $form->item('model_id', '所属用户')->required()->vif('device', false, true)->component(function () use ($edit) {
            return Select::make()
                ->filterable()
                ->extUrlParams(['format' => 'options'])
                ->depend(['device'])
                ->paginate(10)
                ->remote(route('admin.authapi.users'));
        });
        $form->item('router', '路由')->required();
        $form->saving(function (Form $form) {
            if (!$form->model_type) {
                $provider = config('auth.guards.' . $form->device . '.provider');
                $form->model_type = config('auth.providers.' . $provider . '.model');
            }
        });
        return $form;
    }
}
