<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/reservationDAO.php';

class ReservationService extends BaseService {
    public function __construct(){
        parent::__construct(new ReservationDao());
    }
    public function getByUserId($user_id) {
        return $this->dao->getByUserId($user_id);
    }

    public function getByDateRange($start_date, $end_date) {
        return $this->dao->getByDateRange($start_date, $end_date);
    }

    public function isSlotAvailable($date, $time) {
        return $this->dao->isSlotAvailable($date, $time);
    }

    public function cancel($reservation_id) {
        return $this->dao->cancel($reservation_id);
    }

    public function listForDayWithUsers($date) {
        return $this->dao->listForDayWithUsers($date);
    }

    public function createReservation($data) {
        if (isset($data['date'])) {
            $reservation_date = new DateTime($data['date']);
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            
            if ($reservation_date < $today) {
                throw new Exception('Reservation date cannot be in the past.');
            }
        }

        if (isset($data['date']) && isset($data['time'])) {
            if (!$this->isSlotAvailable($data['date'], $data['time'])) {
                throw new Exception('This time slot is already reserved.');
            }
        }

        return $this->add($data);
    }

    public function updateReservation($data, $id) {
        if (isset($data['date'])) {
            $reservation_date = new DateTime($data['date']);
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            
            if ($reservation_date < $today) {
                throw new Exception('Reservation date cannot be in the past.');
            }
        }

        if (isset($data['date']) && isset($data['time'])) {
            $current_reservation = $this->get_by_id($id);
            if ($current_reservation) {
                if ($current_reservation['date'] != $data['date'] || $current_reservation['time'] != $data['time']) {
                    if (!$this->isSlotAvailable($data['date'], $data['time'])) {
                        throw new Exception('This time slot is already reserved.');
                    }
                }
            }
        }

        return $this->update($data, $id);
    }
}

?>

