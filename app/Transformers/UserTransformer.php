<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'id' => $user->id,
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'email' => $user->email,
			'createdAt' => $user->created_at->toRfc3339String(),
			'updatedAt' => $user->updated_at->toRfc3339String()
		];
	}
}
