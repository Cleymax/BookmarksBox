<?php

use App\Database\Query;
use App\Models\Model;
use App\Security\Auth;

class Folders extends Model
{
    public function getAllForMe(): array
    {
        $query = (new Query())
            ->select()
            ->from("folders")
            ->where('user_id = ?', 'parent_id_folder IS NULL')
            ->params([Auth::user()->id]);

        return $query->all();
    }

    public function getAllForTeam(string $team_id): array
    {
        $query = (new Query())
            ->select()
            ->from("folders")
            ->where('team_id = ?', 'parent_id_folder IS NULL')
            ->params([$team_id]);

        return $query->all();
    }

    public function getAllForMeInDir(string $folder_id): array
    {
        $query = (new Query())
            ->select()
            ->from("folders")
            ->where('user_id = ?', 'parent_id_folder = ?')
            ->params([Auth::user()->id, $folder_id]);

        return $query->all();
    }

    public function getAllForTeamInDir(string $team_id, string $folder_id): array
    {
        $query = (new Query())
            ->select()
            ->from("folders")
            ->where('team_id = ?', 'parent_id_folder = ?')
            ->params([$team_id, $folder_id]);

        return $query->all();
    }


    public function delete(string $id)
    {
        $query = (new Query())
            ->delete()
            ->from("folders")
            ->where("id = ?")
            ->params([$id]);

        $query->execute();
    }


}
