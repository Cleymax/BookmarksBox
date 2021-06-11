<?php

use App\Database\Query;
use App\Exceptions\NotFoundException;
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

        if ($query->rowCount() == 0) {
            throw new NotFoundException("Aucun dossier");
        } else {
            return $query->all();
        }
    }
    public function getAllForMeInDir(string $id): array
    {
        $query = (new Query())
            ->select()
            ->from("folders")
            ->where('user_id = ?', 'parent_id_folder = ?')
            ->params([Auth::user()->id, $id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException("Aucun dossier");
        } else {
            return $query->all();
        }
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
