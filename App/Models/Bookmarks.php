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
            ->where('created_by = ?', 'folder IS NULL', 'team_id IS NULL')
            ->params([Auth::user()->id]);

        return $query->all();
    }

    public function getAllForTeam(string $team_id)
    {
        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('team_id = ?', 'folder IS NULL')
            ->params([$team_id]);

        return $query->all();
    }

    public function getAllForMeInDir(string $folder_id): array
    {
        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('created_by = ?', 'folder = ?', 'team_id IS NULL')
            ->params([Auth::user()->id, $folder_id]);

        return $query->all();
    }

    public function getAllForTeamInDir(string $team_id, string $folder_id): array
    {
        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('team_id = ?', 'folder = ?')
            ->params([$team_id, $folder_id]);

        return $query->all();
    }

    public function getFavorite(): array
    {
        $query = (new Query())
            ->select()
            ->into("user_favorite_bookmarks")
            ->inner('bookmarks', 'bookmark_id', 'id')
            ->where('user_id = ?')
            ->params([Auth::user()->id]);

        return $query->all();
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

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function getAllPinForMe(): array
    {
        $query = (new Query())
            ->select()
            ->from("user_favorite_bookmarks")
            ->where('user_id = ?', 'folder IS NULL', 'team_id IS NULL')
            ->params([Auth::user()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException("Aucun favoris");
        } else {
            return $query->all();
        }
    }

    public function addFavorite(string $id)
    {
        $query = (new Query())
            ->insert("user_id", "bookmark_id")
            ->into("user_favorite_bookmarks")
            ->values(["?", "?"])
            ->params([Auth::user()->id, $id])
            ->returning("bookmark_id");

        return $query->first();
    }

    public function isFavorite(string $id)
    {
        $query = (new Query())
            ->select()
            ->from("user_favorite_bookmarks")
            ->where("user_id = ?", "bookmark_id = ?")
            ->params([Auth::user()->id, $id])
            ->returning("bookmark_id");

        return $query->first();
    }

    public function removeFavorite(string $id): void
    {
        $query = (new Query())
            ->delete()
            ->from("user_favorite_bookmarks")
            ->where("user_id = ?", "bookmark_id = ?")
            ->params([Auth::user()->id, $id]);

        $query->execute();
    }

    public function add(string $title, string $link, string $thumbnail, string $difficulty)
    {

        $query = (new Query())
            ->insert("title", "link", "thumbnail", "difficulty", "created_by")
            ->into("bookmarks")
            ->values(["?", "?", "?", "?", "?"])
            ->params([$title, $link, $thumbnail, $difficulty, Auth::user()->id])
            ->returning("id");

        return $query->first();
    }

    public function search(string $recherche)
    {
        $query = (new Query())
            ->select('id', 'thumbnail', 'title', 'reading_time', 'difficulty', 'team_id')
            ->into("bookmarks")
            ->where('(created_by = ? AND LOWER(title) LIKE LOWER(?)) OR (LOWER(title) LIKE LOWER(?) AND team_id  IN (SELECT team_id FROM teams_members WHERE (user_id = ?)))')
            ->params([Auth::user()->id, "%$recherche%", "%$recherche%", Auth::user()->id]);

        return $query->all();
    }
}
