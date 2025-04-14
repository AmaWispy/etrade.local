<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;


    /**
    * Helpers from blog 
    */
        
    //get comments formated 
    protected function getCommentsFormated($id, $page = null, $type): ?array {
        try {
            $comments = null;

            $typeColumn = $type === 'blog' ? 'blog_id' : 'product_id';
    
            $commentsQuery = Comment::where($typeColumn, $id)->where('reply_id', null)->where('reply_user_id', null)->orderByDesc('created_at');

            if ($page === null) {
                $commentsGet = $commentsQuery->get();
            } else {
                $commentsGet = (clone $commentsQuery)->paginate(4, ['*'], 'page', $page);
            }
            
            foreach($commentsGet as $el){
                $commentData = [
                    'id' => $el->id,
                    'user_id' => $el->user_id,
                    'customer_id' => $el->customer_id,
                    'type_id' => $el->type_id,
                    'blog_id' => $el->blog_id,
                    'product_id' => $el->product_id,
                    'reply_user_id' => $el->reply_user_id,
                    'reply_id' => $el->reply_id,
                    'rating' => $el->rating,
                    'content' => $el->content,
                    'created_at' => $el->formattedDate(),
    
                    'comment_user' => $el->user ?? null,
                    'comment_replies' => [],
                ];
            
                foreach ($el->replies as $reply) {
                    $commentData['comment_replies'][] = [
                        'reply' => [
                            'id' => $reply->id,
                            'blog_id' => $reply->blog_id,
                            'product_id' => $reply->blog_id,
                            'content' => $reply->content,
                            'created_at' => $reply->formattedDate(),
                            'customer_id' => $reply->customer_id,
                            'reply_user_id' => $reply->reply_user_id,
                            'reply_id' => $reply->reply_id,
                            'reply_user' => $reply->reply_user,
                            'type_id' => $reply->type_id,
                        ],
                        'comment_reply_user' => $reply->user ?? null, 
                        'reply_user' => $reply->replyUser ?? null, 
                    ];
                }
                
                $comments[] = $commentData;
            }
            
            // Добавляем данные пагинации отдельно
            if ($page !== null) {
                return [
                    'total' => $commentsQuery->count() . ' ' . __('template.comments'),
                    'comments' => $comments,
                    'page' => [
                        'current_page' => $commentsGet->currentPage(),
                        'total_page' => $commentsGet->lastPage(),
                    ],
                ];
            }
            return $comments;

        } catch (\Exception $e) {
            Log::error('Comment Formated Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return null;
        }

    }
}
