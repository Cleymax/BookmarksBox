<?php


use App\Database\Query;
use App\Exceptions\NotFoundException;
use App\Models\Model;
use App\Security\Auth;

class Bookmarks extends Model
{
    public function getAllForMe(): array
    {
        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('created_by = ?', 'folder IS NULL')
            ->params([Auth::user()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException("Aucun favoris");
        } else {
            return $query->all();
        }
    }

    public function getAllPinForMe(): array
    {
        $query = (new Query())
            ->select()
            ->from("user_favorite_bookmarks")
            ->where('user_id = ?', 'folder IS NULL')
            ->params([Auth::user()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException("Aucun favoris");
        } else {
            return $query->all();
        }
    }

    public function edit(string $id, array $values)
    {
        $value = [];
        foreach ($values as $k => $v) {
            $value[$k] = '?';
        }

        $query = (new Query())
            ->update()
            ->into($this->table)
            ->where("id = ?")
            ->set($value)
            ->params(array_merge(array_values($values), [$id]))
            ->returning("id");

        $response = $query->first();

        return $response;
    }

    public function delete(string $id)
    {
        $query = (new Query())
            ->delete()
            ->from("bookmarks")
            ->where("id = ?")
            ->params([$id]);

        $query->execute();
    }

    public function pin(string $id)
    {
        $query = (new Query())
            ->insert("user_id", "bookmark_id")
            ->into("user_favorite_bookmarks")
            ->values(["?", "?"])
            ->params([Auth::user()->id, $id])
            ->returning("bookmark_id");

        $response = $query->first();

        return $response;
    }

    public function removePin(string $id)
    {
        $query = (new Query())
            ->delete()
            ->from("user_favorite_bookmarks")
            ->where("user_id = ?", "bookmark_id = ?")
            ->params([Auth::user()->id, $id]);

        $query->execute();
    }

    public function isPin(string $id){
        $query = (new Query())
            ->select()
            ->from("user_favorite_bookmarks")
            ->where("user_id = ?", "bookmark_id = ?")
            ->params([Auth::User()->id, $id])
            ->returning("bookmark_id");

        $response = $query->first();

        return $response;
    }

    public function add(string $title, string $link, string $thumbnail, string $difficulty)
    {

        $query = (new Query())
            ->insert("title", "link", "thumbnail", "difficulty", "created_by")
            ->into("bookmarks")
            ->values(["?", "?", "?", "?", "?"])
            ->params([$title, $link, $thumbnail, $difficulty, Auth::user()->id])
            ->returning("id");

        $response = $query->first();

        return $response;

    }

}
