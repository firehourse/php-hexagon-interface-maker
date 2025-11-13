<?php

interface OrderRepositoryInterface {
    public function getOrder(int $id): array;
    public function deleteOrder($id);
}
