<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\Query;
use App\Database\QueryApi;
use App\Exceptions\NotFoundException;
use App\Exceptions\TokenNotFoundException;
use App\Security\Auth;
use App\Services\FlashService;

class BookmarkApiController extends Controller
{
    /**
     * BookmarkController constructor.
     */
    public function __construct()
    {
        $this->loadModel('Bookmarks');
    }

    public function getAllBookmarks()
    {
        $query = (new QueryApi())
            ->select()
            ->from('bookmarks')
            ->where('created_by = ?')
            ->params([Auth::userApi()->id]);

        $query->setPossibility(['id', 'title', 'link', 'thumbnail', 'reading_time', 'pin', 'difficulty', 'created_at']);
        $query->setDefault(['id', 'title', 'link', 'created_at']);
        $query->build();

        $this->respond_json([
            'user_id' => Auth::userApi()->id,
            'data' => $query->all(),
        ]);

    }

    public function getBookmark(string $bookmark_id)
    {
        $query = (new QueryApi())
            ->select()
            ->from('bookmarks')
            ->where('created_by = ?', 'id = ?')
            ->params([Auth::userApi()->id, $bookmark_id]);

        $query->setPossibility(['id', 'title', 'link', 'thumbnail', 'reading_time', 'pin', 'difficulty', 'created_at']);
        $query->setDefault(['id', 'title', 'link', 'created_at']);
        $query->build();

        $this->respond_json([
            'user_id' => Auth::userApi()->id,
            'data' => $query->all(),
        ]);

    }

    public function addFavorite(string $bookmark_id)
    {
            $this->Bookmarks->addFavorite($bookmark_id);
            $this->respond_json([
                'type' => 'success',
                'message' => 'Votre bookmarks à bien était ajouter en favoris',
            ]);
    }

    /**
     * @throws TokenNotFoundException
     * @throws NotFoundException
     */
    public function removeFavorite(string $bookmark_id)
    {
        if ( $this->Bookmarks->isFavorite($bookmark_id) == null) {
            throw new NotFoundException('This bookmarks is not in your favorite !');
        }

        $this->Bookmarks->removeFavorite($bookmark_id);

        $this->respond_json([
            'message' => 'Favoris supprimé de vos favoris !'
        ]);
    }

    public function delete(string $bookmark_id)
    {
        if($this->Bookmarks->isFavorite($bookmark_id) != null){
            throw new NotFoundException('Vous ne pouvez pas supprime un bookmarks mis en favoris');
        }else{
            $this->Bookmarks->delete($bookmark_id);
            $this->respond_json([
                'type' => 'success',
                'message' => 'Vous avez bien supprime cette bookmarks',
            ]);
        }
    }

    function move(string $bookmark_id)
    {

    }


}
