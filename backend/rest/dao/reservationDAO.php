<?php
require_once 'baseDao.php';

class ReservationDao extends BaseDao {
    public function __construct() {
        parent::__construct("reservations", "reservation_id");
    }

    public function getByUserId($user_id) {
        return $this->query(
            "SELECT * FROM reservations WHERE user_id = :uid ORDER BY date DESC, time DESC",
            [ 'uid' => $user_id ]
        );
    }

    public function getByDateRange($start_date, $end_date) {
        return $this->query(
            "SELECT * FROM reservations WHERE date BETWEEN :start AND :end ORDER BY date, time",
            [ 'start' => $start_date, 'end' => $end_date ]
        );
    }

    public function isSlotAvailable($date, $time) {
        $row = $this->query_unique(
            "SELECT COUNT(*) AS cnt FROM reservations WHERE date = :d AND time = :t",
            [ 'd' => $date, 't' => $time ]
        );
        return ((int)$row['cnt']) === 0;
    }

    public function cancel($reservation_id) {
        return $this->delete($reservation_id);
    }

    public function listForDayWithUsers($date) {
        return $this->query(
            "SELECT r.*, u.name, u.email
             FROM reservations r
             LEFT JOIN users u ON u.user_id = r.user_id
             WHERE r.date = :d
             ORDER BY r.time",
            [ 'd' => $date ]
        );
    }
}
?>

