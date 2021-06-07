<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\QueryApi;
use App\Security\Auth;

class FolderApiController extends Controller
{

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
            ->where(is_null($id) ? 'parent_id_folder IS NULL' : 'parent_id_folder = ?', 'user_id = ?');

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
}
