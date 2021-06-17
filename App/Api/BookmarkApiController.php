<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\Query;
use App\Database\QueryApi;
use App\Exceptions\NotFoundException;
use App\Exceptions\TokenNotFoundException;
use App\Security\Auth;

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

    public function isFavorite(string $bookmark_id)
    {
        $favorite = $this->Bookmarks->isFavorite($bookmark_id);
        $this->respond_json([
            'isFavorite' => $favorite
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
        if ($this->Bookmarks->isFavorite($bookmark_id) == null) {
            throw new NotFoundException('This bookmarks is not in your favorite !');
        }

        $this->Bookmarks->removeFavorite($bookmark_id);

        $this->respond_json([
            'message' => 'Bookmark supprimé de vos favoris !'
        ]);
    }

    public function delete(string $bookmark_id)
    {
        if ($this->Bookmarks->isFavorite($bookmark_id) != null) {
            throw new NotFoundException('Vous ne pouvez pas supprime un bookmarks mis en favoris');
        } else {
            $this->Bookmarks->delete($bookmark_id);
            $this->respond_json([
                'type' => 'success',
                'message' => 'Vous avez bien supprime cette bookmarks',
            ]);
        }
    }

    function moveBookmark(string $bookmark_id, string $folderId)
    {
        $query = (new Query())
            ->update()
            ->into("bookmarks")
            ->where("id = ?")
            ->set(["folder" => '?'])
            ->params([$folderId, $bookmark_id])
            ->returning("id");

        $query->first();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien déplacer cette bookmarks',
        ]);
    }

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     */
    function createBookmark()
    {
        $data = getBody();
        $json = json_decode($data, true);

        if ($json["parent"] == 'null') {
            if ($json['team'] == 'null') {
                $query = (new Query())
                    ->insert("title", "link", "thumbnail", "difficulty", "description", "created_by")
                    ->into("bookmarks")
                    ->values(["?", "?", "?", "?", "?", "?"])
                    ->params([$json['titleFinal'], $json['linkFinal'], $json['thumbnailFinal'], $json['difficultyFinal'], $json['descriptionFinal'], Auth::userApi()->id])
                    ->returning("id");
            } else {
                $query = (new Query())
                    ->insert("title", "link", "thumbnail", "difficulty", "description", "created_by", "team_id")
                    ->into("bookmarks")
                    ->values(["?", "?", "?", "?", "?", "?", "?"])
                    ->params([$json['titleFinal'], $json['linkFinal'], $json['thumbnailFinal'], $json['difficultyFinal'], $json['descriptionFinal'], Auth::userApi()->id, $json['team'],])
                    ->returning("id");
            }
        } else {
            if ($json['team'] != 'null') {
                $query = (new Query())
                    ->insert("title", "link", "thumbnail", "difficulty", "description", "folder", "created_by", "team_id")
                    ->into("bookmarks")
                    ->values(["?", "?", "?", "?", "?", "?", "?", "?"])
                    ->params([$json['titleFinal'], $json['linkFinal'], $json['thumbnailFinal'], $json['difficultyFinal'], $json['descriptionFinal'], $json["parent"], Auth::userApi()->id, $json['team']])
                    ->returning("id");
            } else {
                $query = (new Query())
                    ->insert("title", "link", "thumbnail", "difficulty", "description", "folder", "created_by")
                    ->into("bookmarks")
                    ->values(["?", "?", "?", "?", "?", "?", "?"])
                    ->params([$json['titleFinal'], $json['linkFinal'], $json['thumbnailFinal'], $json['difficultyFinal'], $json['descriptionFinal'], $json["parent"], Auth::userApi()->id])
                    ->returning("id");
            }
        }
        $query->first();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien déplacer cette bookmarks',
        ]);
    }

    function editBookmark(string $bookmark_id)
    {
        $data = getBody();
        $json = json_decode($data, true);

        $query = (new Query())
            ->update()
            ->into("bookmarks")
            ->where("id = ?")
            ->set(["title" => '?', "link" => '?', "thumbnail" => '?', "difficulty" => '?', "description" => '?'])
            ->params([$json["title"], $json["link"], $json["thumbnail"], $json["difficulty"], $json["description"], $bookmark_id])
            ->returning("id");

        $query->execute();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien modifier cette bookmarks',
        ]);

    }
}
