<?php

namespace Pingu\Content\Accessors;

use Illuminate\Support\Str;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Contracts\EntityAccessorBase;

class ContentAccessor extends EntityAccessorBase
{
	public function view(): bool
	{
		return \Gate::allows('view-content', $this->entity);
	}

	public function edit(): bool
	{
		return \Gate::allows('edit-content', $this->entity);
	}

	public function delete(): bool
	{
		return \Gate::allows('delete-content', $this->entity);
	}

	public function create(BundleContract $type): bool
	{
		return \Gate::allows('create-content', $type);
	}
}