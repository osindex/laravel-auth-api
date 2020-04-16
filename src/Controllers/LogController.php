<?php

namespace Osi\AuthApi\Controllers;

use Osi\AuthApi\Models\ApiLog;
use SmallRuralDog\Admin\Components\Avatar;
use SmallRuralDog\Admin\Components\SelectOption;
use SmallRuralDog\Admin\Components\Tag;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class LogController extends AdminController
{

    protected function grid()
    {

        $grid = new Grid(new ApiLog());
        $grid->perPage(15)
            ->quickSearch()
            ->selection()
            ->defaultSort('id', 'desc')
            ->stripe()
            ->emptyText("暂无日志");

        $grid->column('id', "ID")->width("100");
        $grid->column('model.avatar', '头像', 'model_id')->component(Avatar::make()->size('small'))->width(80);
        $grid->column('model.name', '用户', 'model_id')->help("操作用户")->sortable();
        $grid->column('device', '应用/类型')->align('center')->customValue(function ($row, $value) {
            $getDevicesOptions = getDevicesOptions(false);
            return isset($getDevicesOptions[$value]) ? $getDevicesOptions[$value]['label'] : $value;
        })->component(Tag::make());
        $grid->column('method', '请求方式')->width(100)->align('center')->component(Tag::make()->type(['GET' => 'info', 'POST' => 'success']));
        $grid->column('path', '路径')->help('操作URL')->sortable();
        $grid->column('ip', 'IP');
        $grid->column('created_at', "创建时间")->sortable();

        $grid->actions(function (Grid\Actions $actions) {
            $actions->hideEditAction();
            $actions->hideViewAction();
        })->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->hideCreateButton();
        });
        return $grid;
    }

    protected function form()
    {
        $form = new Form(new ApiLog());

        return $form;
    }
}
function getDevicesOptions($htmlOptions = true)
{
    return collect(config('auth.guards'))->filter(function ($item, $key) {
        return $item['driver'] === 'sanctum' && $key !== 'sanctum';
    })->map(function ($item) use ($htmlOptions) {
        $label = $item['name'] ?? $item['provider'];
        return $htmlOptions ? SelectOption::make($item['provider'], $label) : ['value' => $item['provider'], 'label' => $label];
    })->toArray();
}
