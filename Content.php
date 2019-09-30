<?php

namespace Pingu\Content;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Str;
use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Entities\Content as ContentModel;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Exceptions\ContentFieldAlreadyExists;
use Pingu\Core\Exceptions\ClassException;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Exceptions\ModelNotFormable;
use Pingu\Forms\Support\Field as FormField;
use Pingu\Forms\Support\Fields\Submit;

class Content
{
	/**
	 * Creates a new content
	 * @param  ContentType $type
	 * @param  array       $values
	 * @return Content
	 */
	public function createContent(ContentType $type, array $values)
	{
		$content = new ContentModel();
        $content->content_type()->associate($type);
        $content->creator()->associate(\Auth::user());
        $content->slug = $content->generateSlug(Str::slug($values['title']));
        $content->save();
        $content->saveFieldValues($values);
        return $content;
	}

	/**
	 * Updates a content
	 * @param  Content $content
	 * @param  array   $values
	 * @return Content
	 */
	public function updateContent(ContentModel $content, array $values)
	{
		$content->slug = $content->generateSlug(Str::slug($values['title']), $content);
        $content->save();
        $content->saveFieldValues($values);
        return $content;
	}
}