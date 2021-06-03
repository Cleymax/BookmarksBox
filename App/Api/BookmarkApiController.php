<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\Query;
use App\Exceptions\NotFoundException;
use App\Security\Auth;

class BookmarkApiController extends Controller
{

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\NotFoundException
     */
    public function removeFavorite(string $bookmark_id)
    {
        $query = (new Query())
            ->select()
            ->from('user_favorite_bookmarks')
            ->where('bookmark_id = ?', 'user_id = ?')
            ->params([$bookmark_id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('This bookmarks is not in your favorite !');
        }

        $query = (new Query())
            ->delete()
            ->from('user_favorite_bookmarks')
            ->where('bookmark_id = ?', 'user_id = ?')
            ->params([$bookmark_id, Auth::userApi()->id]);

        $query->execute();
        $this->respond_json([
            'message' => 'Favoris supprimé de vos favoris !'
        ]);
    }
}
