<?php
function getStylists(mysqli $conn): array {
    $stylists = [];
    $query = "SELECT id, name FROM users WHERE role = 'stylist'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $stylists[] = $row;
    }
    return $stylists;
}

function getStylistName($stylistId, $conn) {
    if (!$stylistId) return '-';
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $stylistId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    return $row['name'] ?? '-';
}
?>