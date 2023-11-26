<?php

namespace App\Nova;

use App\Nova\Actions\ChangeCreatedAt;
use App\Nova\Actions\Jobijoba;
use App\Nova\Lenses\UserLens;
use App\Rules\RequiredFile;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lupennat\NestedMany\Fields\HasManyNested;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * The click action to use when clicking on the resource in the table.
     * Can be one of: 'detail' (default), 'edit', 'select', 'preview', or 'ignore'.
     *
     * @var string
     */
    public static $clickAction = 'ignore';

    /**
     * Indicates whether the resource should automatically poll for new resources.
     *
     * @var bool
     */
    public static $polling = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

//            DateTime::make('created_at'),
//
//            File::make('File')
//                ->rules([new RequiredFile]),
//
//            Text::make('Test')
//                ->rules([new RequiredFile]),
//
//            Text::make('Test2')
//                ->rules(new RequiredFile), // doesn't work without array


//
//            BelongsTo::make('Post')
//                ->hide()
//                ->nullable()
//                ->searchable()
//                ->showCreateRelationButton()
//                ->dependsOn('size', function (BelongsTo $field, NovaRequest $request, FormData $formData) {
//                    if ($formData->size === 'S') {
//                        $field->show();
//                    } else {
//                        $field->setValue(null);
//                    }
//                }),
//
//            Text::make('Post Value')
//                ->dependsOn('post', function (Text $field, NovaRequest $request, FormData $formData) {
//                    $field->setValue($formData->post);
//                })

            Select::make('Size')->options([
                'S' => 'Small',
                'M' => 'Medium',
                'L' => 'Large',
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            new UserLens()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            //ChangeCreatedAt::make()->sole(),
            Jobijoba::make(),
            ChangeCreatedAt::make()->onlyInline(),
        ];
    }
}
