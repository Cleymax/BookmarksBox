<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\Query;
use App\Database\QueryApi;
use App\Security\Auth;

class FolderApiController extends Controller
{

    public function __construct()
    {
        $this->loadModel('Folders');
    }

    /**
     * @throws \App\Exceptions\UnknownFieldException
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\ProtectFieldException
     * @throws \App\Exceptions\InvalidParamException
     */
    public function getFolderById(?string $id)
    {
        $query = (new QueryApi())
            ->select()
            ->from('folders')
            ->where(is_null($id) ? 'parent_id_folder IS NULL' : 'parent_id_folder = ?', 'user_id = ?', 'team_id IS NULL');

        if (is_null($id)) {
            $query->params([Auth::userApi()->id]);
        } else {
            $query->params([$id, Auth::userApi()->id]);
        }
        $query->setDefault(['id', 'name', 'parent_id_folder', 'color', 'team_id', 'user_id']);
        $query->setPossibility(['id', 'created_at', 'modified_at', 'name', 'parent_id_folder', 'color', 'team_id', 'user_id']);
        $query->build();
        $query->execute();

        $this->respond_json([
            'count' => $query->rowCount(),
            'data' => $query->all()
        ]);
    }


    /**
     * @throws \App\Exceptions\UnknownFieldException
     * @throws \App\Exceptions\ProtectFieldException
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\InvalidParamException
     */
    public function getFolders()
    {
        $this->getFolderById(null);
    }

    public function getAllFolders()
    {
        $query = (new QueryApi())
            ->select()
            ->from('folders')
            ->where('user_id = ?')
            ->params([Auth::userApi()->id]);

        $query->setPossibility(['id', 'name', 'parent_id_folder', 'color', 'team_id', 'user_id', 'created_at']);
        $query->setDefault(['id', 'name', 'color', 'created_at']);
        $query->build();

        $this->respond_json([
            'user_id' => Auth::userApi()->id,
            'data' => $query->all(),
        ]);
    }

    public function getFolder(string $folder_id)
    {
        $query = (new QueryApi())
            ->select()
            ->from('folders')
            ->where('user_id = ?', 'id = ?')
            ->params([Auth::userApi()->id, $folder_id]);

        $query->setPossibility(['id', 'name', 'parent_id_folder', 'color', 'team_id', 'user_id', 'created_at']);
        $query->setDefault(['id', 'name', 'color', 'created_at']);
        $query->build();

        $this->respond_json([
            'user_id' => Auth::userApi()->id,
            'data' => $query->all(),
        ]);

    }

    public function deleteFolder(string $folder_id)
    {
        $this->Folders->delete($folder_id);

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien supprime ce dossier',
        ]);
    }

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     */
    public function createFolder()
    {
        $data = getBody();
        $json = json_decode($data, true);

        if ($json['parent'] == 'null') {
            if ($json['team'] == 'null') {
                $query = (new Query())
                    ->insert("name", "color", "user_id")
                    ->into("folders")
                    ->values(["?", "?", "?"])
                    ->params([$json["name"], $json["color"], Auth::userApi()->id]);
            }else {
                $query = (new Query())
                    ->insert("name", "color", "user_id","team_id")
                    ->into("folders")
                    ->values(["?", "?", "?", "?"])
                    ->params([$json["name"], $json["color"], Auth::userApi()->id, $json['team']]);
            }

        } else {
            if ($json['team'] == 'null') {
                $query = (new Query())
                    ->insert("name", "color", "parent_id_folder", "user_id")
                    ->into("folders")
                    ->values(["?", "?", "?", "?"])
                    ->params([$json["name"], $json["color"], $json['parent'], Auth::userApi()->id]);
            }else {
                $query = (new Query())
                    ->insert("name", "color", "parent_id_folder", "user_id", "team_id")
                    ->into("folders")
                    ->values(["?", "?", "?", "?", "?"])
                    ->params([$json["name"], $json["color"], $json['parent'], Auth::userApi()->id, $json['team']]);
            }

        }

        $query->execute();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien crée un nouveau dossier',
        ]);
    }

    public function isFolder(string $id)
    {
        $query = (new Query())
            ->select()
            ->from('bookmarks')
            ->where('created_by = ?', 'id = ?')
            ->params([Auth::userApi()->id, $id])
            ->returning("id");

        $response = $query->first();

        $this->respond_json([
            'type' => 'success',
            'result' => $response,
        ]);
    }

    function moveFolder(string $folderId, string $parentFolderId)
    {
        $query = (new Query())
            ->update()
            ->into("folders")
            ->where("id = ?")
            ->set(["parent_id_folder" => '?'])
            ->params([$parentFolderId, $folderId])
            ->returning("id");

        $query->first();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien déplacer ce dossier',
        ]);
    }

    function getFolderBookmark(string $folderId)
    {
        $query = (new QueryApi())
            ->select()
            ->from('bookmarks')
            ->where('folder = ?')
            ->params([$folderId]);

        $query->setPossibility(['id', 'title', 'link', 'thumbnail', 'reading_time', 'pin', 'difficulty', 'created_at']);
        $query->setDefault(['id', 'title', 'link', 'created_at']);
        $query->build();

        $this->respond_json([
            'parent_folder' => $folderId,
            'data' => $query->all(),
        ]);
    }

    function editFolder(string $folder_id)
    {
        $data = getBody();
        $json = json_decode($data, true);

        $query = (new Query())
            ->update()
            ->into("folders")
            ->where("id = ?")
            ->set(["name" => '?', "color" => '?'])
            ->params([$json["name"], $json["color"], $folder_id])
            ->returning("id");

        $query->execute();

        $this->respond_json([
            'type' => 'success',
            'message' => 'Vous avez bien modifier ce dossier',
        ]);

    }


}
