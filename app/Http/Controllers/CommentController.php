<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required',
            'commentable_id' => 'required',
            'content' => 'required|max:500',
        ], ['content.required' => 'Vous ne pouvez pas envoyer un commentaire vide.',
            'content.max' => 'Le commentaire ne peut pas dépasser 500 caractéres.',

        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
        ]);

        return back()->with('success', 'Commentaire ajouté.');
    }
}
