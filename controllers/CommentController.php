<?php
class CommentController extends Controller {

    public function show() {
        $commentModel = $this->model("CommentModel");
        $comments = $commentModel->getAll();

        $this->view("adminPage", [
            "page" => "CommentListView",
            "comments" => $comments
        ]);
    }

    public function approve($id) {
        $commentModel = $this->model("CommentModel");
        $commentModel->updateStatus($id, 1);
        header("Location: " . APP_URL . "/CommentController/show");
        exit();
    }

    public function unapprove($id) {
        $commentModel = $this->model("CommentModel");
        $commentModel->updateStatus($id, 0);
        header("Location: " . APP_URL . "/CommentController/show");
        exit();
    }

    public function delete($id) {
        $commentModel = $this->model("CommentModel");
        $commentModel->delete($id);
        header("Location: " . APP_URL . "/CommentController/show");
        exit();
    }
}
?>