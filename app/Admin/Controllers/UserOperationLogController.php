<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;
use App\Models\UserOperationLog;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Controllers\AdminController;

class UserOperationLogController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return "User Log";
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserOperationLog());

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'User');
        $grid->column('method')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->column('path')->label('info');
        $grid->column('ip')->label('primary');
        $grid->column('input')->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre>' . json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG) . '</pre>';
        });

        $grid->column('created_at', trans('admin.created_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $userModel = User::class;

            $filter->equal('user_id', 'User')->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
            $filter->like('path');
            $filter->equal('ip');
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new UserOperationLog);

        $form->display('ID');
        $form->text('user_id', 'user_id');
        $form->text('path', 'path');
        $form->text('method', 'method');
        $form->text('ip', 'ip');
        $form->text('input', 'input');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
