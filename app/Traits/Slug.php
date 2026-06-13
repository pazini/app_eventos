<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slug
{
    public static function bootSlug()
    {
        static::creating(function ($model) {

            if ($model->nome ?? false)
                $text = $model->nome;
            else if ($model->apelido ?? false)
                $text = $model->apelido;
            else if ($model->title ?? false)
                $text = $model->title;
            else if ($model->value ?? false)
                $text = $model->value;
            else if ($model->description ?? false)
                $text = $model->description;
            else
                $text = 'timestamp';

            $model->slug = static::generateSlug($text);
        });

        static::saving(function ($model) {

            if ($model->nome ?? false)
                $text = $model->nome;
            else if ($model->apelido ?? false)
                $text = $model->apelido;
            else if ($model->title ?? false)
                $text = $model->title;
            else if ($model->value ?? false)
                $text = $model->value;
            else if ($model->description ?? false)
                $text = $model->description;
            else
                $text = now()->timestamp;

            $model->slug = static::generateSlug($text, $model->id);
        });
    }

    public static function generateSlug($name, $id = false)
    {
        $slug = Str::slug($name);

        if (in_array(env('DB_CONNECTION'), ['mysql'])) {
            if ($id)
                $count = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->whereNotIn('id', [$id])->count();
            else
                $count = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
        } else if (in_array(env('DB_CONNECTION'), ['sqlite'])) {
            if ($id)
                $count = static::where("slug", "like", "{$slug}-%")->whereNotIn('id', [$id])->count();
            else
                $count = static::where("slug", "like", "{$slug}-%")->count();
        } else {
            if ($id)
                $count = static::whereRaw("slug ~ '^{$slug}(-[0-9]*)?$'")->whereNotIn('id', [$id])->count();
            else
                $count = static::whereRaw("slug ~ '^{$slug}(-[0-9]*)?$'")->count();
        }

        if ($count)
            return Str::slug($name) . '-' . $count;
        else
            return Str::slug($name);
    }
}
