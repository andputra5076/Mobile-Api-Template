<?php
// Include PHPExcel or PhpSpreadsheet library
require '../vendor/autoload.php'; // Assuming PhpSpreadsheet is installed via Composer
use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Include database configuration
include '../config.php';

// Get request method and endpoint
$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$endpoint = explode('/', $path);

// Check if we have enough parts in the endpoint and handle accordingly
$resource = isset($endpoint[2]) ? $endpoint[2] : '';

// Routing based on endpoint and method
switch ($resource) {
    case 'tambah-sekolah':
        handleTambahSekolah($method);
        break;
    case 'edit-sekolah':
        handleEditSekolah($method);
        break;
    case 'hapus-sekolah':
        handleHapusSekolah($method);
        break;
    case 'get-murid':
        handleMurid($method);
        break;
    case 'get-jadwal-hafalan':
        handleJadwalHafalan($method);
        break;
    case 'get-admin':
        handleAdmin($method);
        break;
    case 'get-tahun':
        handleTahun($method);
        break;
    case 'tambah-tahun':
        handleTambahTahun($method);
        break;
    case 'edit-tahun':
        handleEditTahun($method);
        break;
    case 'edit-tahunstatus':
        handleEditTahunStatus($method);
        break;
    case 'hapus-tahun':
        handleHapusTahun($method);
        break;
    case 'get-guru':
        handleGuru($method);
        break;
    case 'get-berita':
        handleBerita($method);
        break;
    case 'tambah-berita':
        handleTambahBerita($method);
        break;
    case 'edit-berita':
        handleEditBerita($method);
        break;
    case 'hapus-berita':
        handleHapusBerita($method);
        break;
    case 'tambah-guru':
        handleTambahGuru($method);
        break;
    case 'tambah-murid':
        handleTambahMurid($method);
        break;
    case 'edit-guru':
        handleEditGuru($method);
        break;
    case 'edit-murid':
        handleEditMurid($method);
        break;
    case 'edit-admin':
        handleEditAdmin($method);
        break;
    case 'get-sekolah':
        handleSekolah($method);
        break;
    case 'get-pendaftaran':
        handlePendaftaran($method);
        break;
    case 'get-sekolah_id':
        handleSekolahbdyid($method);
        break;
    case 'get-kelas':
        handleKelas($method);
        break;
    case 'get-operator':
        handleOperator($method);
        break;
    case 'tambah-operator':
        handleTambahOperator($method);
        break;
    case 'edit-operator':
        handleEditOperator($method);
        break;
    case 'tambah-kelas':
        handleTambahKelas($method);
        break;
    case 'tambah-pendaftaran':
        handleTambahPendaftaran($method);
        break;
    case 'tambah-jadwal-hafalan':
        handleTambahJadwalHafalan($method);
        break;
    case 'edit-jadwal-hafalan':
        handleEditJadwalHafalan($method);
        break;
    case 'hapus-jadwal-hafalan':
        handleHapusJadwalHafalan($method);
        break;
    case 'import-kelas':
        handleImportKelas($method);
        break;
    case 'import-guru':
        handleImportGuru($method);
        break;
    case 'import-murid':
        handleImportMurid($method);
        break;
    case 'edit-kelas':
        handleEditKelas($method);
        break;
    case 'hapus-kelas':
        handleHapusKelas($method);
        break;
    case 'hapus-murid':
        handleHapusMurid($method);
        break;
    case 'hapus-guru':
        handleHapusGuru($method);
        break;
    case 'hapus-operator':
        handleHapusOperator($method);
        break;
    case 'login':
        handleLogin($method);
        break;
    case 'login-app':
        handleLoginApp($method);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid endpoint']);
        break;
}

function handleMurid($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            // Update the query to include JOINs with the kelas and sekolah tables
            $query = $id ? "
                SELECT murid.*, kelas.kelas, kelas.id_sekolah, sekolah.nama_sekolah
                FROM murid 
                LEFT JOIN kelas ON murid.id_kelas = kelas.id 
                LEFT JOIN sekolah ON kelas.id_sekolah = sekolah.id
                WHERE murid.id = ?
            " : "
                SELECT murid.*, kelas.kelas, kelas.id_sekolah, sekolah.nama_sekolah
                FROM murid 
                LEFT JOIN kelas ON murid.id_kelas = kelas.id
                LEFT JOIN sekolah ON kelas.id_sekolah = sekolah.id
            ";

            $stmt = $mysqli->prepare($query);

            if ($id) {
                $stmt->bind_param('i', $id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch rows based on whether it's a single record or all records
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode($rows);
            break;
    }
}

function handleJadwalHafalan($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            // Update the query to include JOINs with the kelas and guru tables
            $query = $id ? "
                SELECT jadwal_hafalan.*, guru.nama_lengkap, guru.nip, guru.alamat, guru.email, guru.no_telepon, kelas.kelas
                FROM jadwal_hafalan 
                LEFT JOIN guru ON jadwal_hafalan.id_guru = guru.id
                LEFT JOIN kelas ON guru.id_kelas = kelas.id
                WHERE jadwal_hafalan.id = ?
            " : "
                SELECT jadwal_hafalan.*, guru.nama_lengkap, guru.nip, guru.alamat, guru.email, guru.no_telepon, kelas.kelas
                FROM jadwal_hafalan 
                LEFT JOIN guru ON jadwal_hafalan.id_guru = guru.id
                LEFT JOIN kelas ON guru.id_kelas = kelas.id
            ";

            $stmt = $mysqli->prepare($query);

            if ($id) {
                $stmt->bind_param('i', $id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch rows based on whether it's a single record or all records
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode($rows);
            break;
    }
}

function handleAdmin($method)
{
    global $mysqli;

    if ($method === 'GET') {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($id) {
            // Fetch admin by ID
            $query = "SELECT * FROM admin WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                echo json_encode($row);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Admin not found']);
            }
        } else {
            // Fetch all admins
            $query = "SELECT * FROM admin";
            $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode($rows);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    }
}


function handleGuru($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            // Update the query to include JOINs with the kelas and sekolah tables
            $query = $id ? "
                SELECT guru.*, kelas.kelas, kelas.id_sekolah, sekolah.nama_sekolah
                FROM guru 
                LEFT JOIN kelas ON guru.id_kelas = kelas.id 
                LEFT JOIN sekolah ON kelas.id_sekolah = sekolah.id
                WHERE guru.id = ?
            " : "
                SELECT guru.*, kelas.kelas, kelas.id_sekolah, sekolah.nama_sekolah
                FROM guru 
                LEFT JOIN kelas ON guru.id_kelas = kelas.id
                LEFT JOIN sekolah ON kelas.id_sekolah = sekolah.id
            ";

            $stmt = $mysqli->prepare($query);

            if ($id) {
                $stmt->bind_param('i', $id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch rows based on whether it's a single record or all records
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode($rows);
            break;
    }
}

function handleBerita($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            // Update the query to include JOINs with the kelas and sekolah tables
            $query = $id ? "
                SELECT berita.*, operator.fullname, operator.username, operator.email, operator.phone_number, operator.address, sekolah.nama_sekolah
                FROM berita 
                LEFT JOIN operator ON berita.id_operator = operator.id 
                LEFT JOIN sekolah ON operator.id_sekolah = sekolah.id 
                WHERE berita.id = ?
            " : "
                SELECT berita.*, operator.fullname, operator.username, operator.email, operator.phone_number, operator.address, sekolah.nama_sekolah
                FROM berita 
                LEFT JOIN operator ON berita.id_operator = operator.id
                LEFT JOIN sekolah ON operator.id_sekolah = sekolah.id
            ";

            $stmt = $mysqli->prepare($query);

            if ($id) {
                $stmt->bind_param('i', $id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch rows based on whether it's a single record or all records
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode($rows);
            break;
    }
}

function handleSekolahbdyid()
{
    global $mysqli;
    //use post
    $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;
    if ($id_sekolah) {
        //get sekolah
        $stmt = $mysqli->prepare("SELECT * FROM sekolah WHERE id = ?");
        $stmt->bind_param('i', $id_sekolah);
        $stmt->execute();
        $result = $stmt->get_result();
        $sekolah = $result->fetch_all(MYSQLI_ASSOC);
        //add count kelas in $sekolah
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM kelas WHERE id_sekolah = ?");
        $stmt->bind_param('i', $id_sekolah);
        $stmt->execute();
        $result = $stmt->get_result();
        $kelas = $result->fetch_all(MYSQLI_ASSOC);
        $sekolah['kelas'] = $kelas[0]['COUNT(*)'];
        echo json_encode($sekolah);
    }
}

function handleSekolah($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
            $query = $id ? "SELECT * FROM sekolah WHERE id = ?" : "SELECT * FROM sekolah";
            $stmt = $mysqli->prepare($query);
            if ($id) {
                $stmt->bind_param('i', $id);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($rows);
            break;
    }
}

function handlePendaftaran($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
            $query = $id ? "SELECT * FROM pendaftaran WHERE id = ?" : "SELECT * FROM pendaftaran";
            $stmt = $mysqli->prepare($query);
            if ($id) {
                $stmt->bind_param('i', $id);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($rows);
            break;
    }
}

function handleGetSekolahById()
{
    global $mysqli;
    //use post
    $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;
    if ($id_sekolah) {
        //get sekolah
        $stmt = $mysqli->prepare("SELECT * FROM sekolah WHERE id = ?");
        $stmt->bind_param('i', $id_sekolah);
        $stmt->execute();
        $result = $stmt->get_result();
        $sekolah = $result->fetch_all(MYSQLI_ASSOC);
        //add count kelas in $sekolah
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM kelas WHERE id_sekolah = ?");
        $stmt->bind_param('i', $id_sekolah);
        $stmt->execute();
        $result = $stmt->get_result();
        $kelas = $result->fetch_all(MYSQLI_ASSOC);
        $sekolah['kelas'] = $kelas[0]['COUNT(*)'];
        echo json_encode($sekolah);
    }
}

function handleOperator($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            if ($id) {
                // Query to get a single operator with associated school name
                $query = "SELECT operator.*, sekolah.nama_sekolah
                          FROM operator
                          JOIN sekolah ON operator.id_sekolah = sekolah.id 
                          WHERE operator.id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('i', $id);
            } else {
                // Query to get all operators with associated school names
                $query = "SELECT operator.*,sekolah.nama_sekolah 
                          FROM operator
                          JOIN sekolah ON operator.id_sekolah = sekolah.id";
                $stmt = $mysqli->prepare($query);
            }

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($rows);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            break;
    }
}

function handleKelas($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            if ($id) {
                // Query to get a single operator with associated school name
                $query = "SELECT kelas.*,sekolah.nama_sekolah
                          FROM kelas
                          JOIN sekolah ON kelas.id_sekolah = sekolah.id 
                          WHERE kelas.id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('i', $id);
            } else {
                // Query to get all operators with associated school names
                $query = "SELECT kelas.*,sekolah.nama_sekolah 
                          FROM kelas
                          JOIN sekolah ON kelas.id_sekolah = sekolah.id";
                $stmt = $mysqli->prepare($query);
            }

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($rows);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            break;
    }
}

function handleTahun($method)
{
    global $mysqli;
    switch ($method) {
        case 'GET':
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;

            if ($id) {
                // Query to get a single operator with associated school name
                $query = "SELECT tahun_akademik.*,sekolah.nama_sekolah
                          FROM tahun_akademik
                          JOIN sekolah ON tahun_akademik.id_sekolah = sekolah.id 
                          WHERE tahun_akademik.id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('i', $id);
            } else {
                // Query to get all operators with associated school names
                $query = "SELECT tahun_akademik.*,sekolah.nama_sekolah 
                          FROM tahun_akademik
                          JOIN sekolah ON tahun_akademik.id_sekolah = sekolah.id";
                $stmt = $mysqli->prepare($query);
            }

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            $rows = $id ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($rows);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            break;
    }
}

// Functions for new endpoints
function handleTambahSekolah($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $nama_sekolah = isset($_POST['nama_sekolah']) ? $_POST['nama_sekolah'] : null;
        $npsn = isset($_POST['npsn']) ? $_POST['npsn'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $phone_number_sekolah = isset($_POST['phone_number_sekolah']) ? $_POST['phone_number_sekolah'] : null;
        $email_sekolah = isset($_POST['email_sekolah']) ? $_POST['email_sekolah'] : null;

        // Check if all fields are provided
        if ($nama_sekolah && $npsn && $alamat && $phone_number_sekolah && $email_sekolah) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO sekolah (nama_sekolah, npsn, alamat, phone_number_sekolah, email_sekolah, created_at, updated_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sssss', $nama_sekolah, $npsn, $alamat, $phone_number_sekolah, $email_sekolah);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'School added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add school']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditSekolah($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nama_sekolah = isset($_POST['nama_sekolah']) ? $_POST['nama_sekolah'] : null;
        $npsn = isset($_POST['npsn']) ? $_POST['npsn'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null; // Ensure 'alamat' matches the form field
        $phone_number_sekolah = isset($_POST['phone_number_sekolah']) ? $_POST['phone_number_sekolah'] : null;
        $email_sekolah = isset($_POST['email_sekolah']) ? $_POST['email_sekolah'] : null;

        // Check if all required fields are provided
        if ($id && $nama_sekolah && $npsn && $alamat && $phone_number_sekolah && $email_sekolah) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("UPDATE sekolah SET nama_sekolah = ?, npsn = ?, alamat = ?, phone_number_sekolah = ?, email_sekolah = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sssssi', $nama_sekolah, $npsn, $alamat, $phone_number_sekolah, $email_sekolah, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'School updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update school or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahKelas($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $kelas = isset($_POST['kelas']) ? $_POST['kelas'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($kelas && $id_operator) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO kelas (kelas, id_operator) VALUES (?, ?)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('si', $kelas, $id_operator);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Class added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add class']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahPendaftaran($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $nama_sekolah = isset($_POST['nama_sekolah']) ? $_POST['nama_sekolah'] : null;
        $npsn = isset($_POST['npsn']) ? $_POST['npsn'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $telp = isset($_POST['telp']) ? $_POST['telp'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;

        // Check if all required fields are provided
        if ($nama_sekolah && $npsn && $alamat && $telp && $email) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO pendaftaran (nama_sekolah, npsn, alamat, telp, email) VALUES (?, ?, ?, ?, ?)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sssss', $nama_sekolah, $npsn, $alamat, $telp, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Registration added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add registration']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleTambahJadwalHafalan($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id_guru = isset($_POST['id_guru']) ? $_POST['id_guru'] : null;
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
        $tanggal_akhir = isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : null;
        $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : null;

        // Check if all required fields are provided
        if ($id_guru && $tanggal && $tanggal_akhir && $deskripsi) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO jadwal_hafalan (id_guru, tanggal, tanggal_akhir, deskripsi) VALUES (?, ?, ?, ?)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('isss', $id_guru, $tanggal, $tanggal_akhir, $deskripsi);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal hafalan added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add jadwal hafalan']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahTahun($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
        $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($tahun && $id_sekolah) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO tahun_akademik (tahun, id_sekolah, id_operator) VALUES (?, ?, ?)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sii', $tahun, $id_sekolah, $id_operator);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Tahun added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add tahun']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahBerita($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $judul = isset($_POST['judul']) ? $_POST['judul'] : null;
        $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : null;
        $gambar = isset($_POST['gambar']) ? $_POST['gambar'] : null;
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($judul && $deskripsi && $gambar && $tanggal && $id_operator) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO berita (judul, deskripsi, gambar, tanggal, id_operator) VALUES (?, ?, ?, ?, ?)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('ssssi', $judul, $deskripsi, $gambar, $tanggal, $id_operator);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Berita added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add berita']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleImportGuru($method)
{
    global $mysqli;

    if ($method === 'POST') {
        if (isset($_FILES['file_excel']) && $_FILES['file_excel']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_excel']['tmp_name'];
            $fileName = $_FILES['file_excel']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if ($fileExtension != 'xlsx') {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
                return;
            }

            try {
                $spreadsheet = IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Remove the header row
                $header = array_shift($data);

                // Check if header is correct
                if ($header[0] !== 'nama lengkap' || $header[1] !== 'nip' || $header[2] !== 'alamat' || $header[3] !== 'telp' || $header[4] !== 'email' || $header[5] !== 'kelas') {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Invalid file format']);
                    return;
                }

                // Process each row
                foreach ($data as $row) {
                    $nama_lengkap = $row[0];
                    $nip = $row[1];
                    $alamat = $row[2];
                    $telp = $row[3];
                    $email = $row[4];
                    $kelas = $row[5];

                    // Generate password using MD5 hash of NIP
                    $password = md5($nip);

                    // Fetch the class ID based on the class name
                    $stmt = $mysqli->prepare("SELECT id AS id_kelas FROM kelas WHERE kelas = ?");
                    $stmt->bind_param('s', $kelas);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $kelasData = $result->fetch_assoc();
                        $id_kelas = $kelasData['id_kelas'];

                        // Check if the teacher already exists
                        $stmt = $mysqli->prepare("SELECT id FROM guru WHERE nip = ? AND id_kelas = ?");
                        $stmt->bind_param('si', $nip, $id_kelas);
                        $stmt->execute();
                        $existingGuruResult = $stmt->get_result();

                        if ($existingGuruResult->num_rows > 0) {
                            // Update existing record
                            $stmt = $mysqli->prepare("UPDATE guru SET nama_lengkap = ?, alamat = ?, telp = ?, id_kelas = ?, password = ?, email = ? WHERE nip = ?");
                            $stmt->bind_param('sssiss', $nama_lengkap, $alamat, $telp, $id_kelas, $password, $email, $nip);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to update teacher: ' . $stmt->error]);
                                return;
                            }
                        } else {
                            // Insert new record
                            $stmt = $mysqli->prepare("INSERT INTO guru (nama_lengkap, nip, alamat, telp, id_kelas, password, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param('sssiiss', $nama_lengkap, $nip, $alamat, $telp, $id_kelas, $password, $email);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to add teacher: ' . $stmt->error]);
                                return;
                            }
                        }
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Class not found']);
                        return;
                    }
                }

                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Guru updated/added successfully']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'File is missing or upload error']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleImportKelas($method)
{
    global $mysqli;

    if ($method === 'POST') {
        if (isset($_FILES['file_excel']) && $_FILES['file_excel']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_excel']['tmp_name'];
            $fileName = $_FILES['file_excel']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if ($fileExtension != 'xlsx') {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
                return;
            }

            try {
                $spreadsheet = IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Remove the header row
                $header = array_shift($data);

                // Check if header is correct
                if ($header[0] !== 'kelas' || $header[1] !== 'sekolah') {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Invalid file format']);
                    return;
                }

                // Process each row
                foreach ($data as $row) {
                    $kelas = $row[0];
                    $nama_sekolah = $row[1];

                    // Fetch the school ID based on school name
                    $stmt = $mysqli->prepare("SELECT id FROM sekolah WHERE nama_sekolah = ?");
                    $stmt->bind_param('s', $nama_sekolah);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $sekolah = $result->fetch_assoc();
                        $id_sekolah = $sekolah['id'];

                        // Check if the class already exists for the given school
                        $stmt = $mysqli->prepare("SELECT id FROM kelas WHERE kelas = ? AND id_sekolah = ?");
                        $stmt->bind_param('si', $kelas, $id_sekolah);
                        $stmt->execute();
                        $existingClassResult = $stmt->get_result();

                        if ($existingClassResult->num_rows > 0) {
                            // Update existing record
                            $stmt = $mysqli->prepare("UPDATE kelas SET kelas = ? WHERE kelas = ? AND id_sekolah = ?");
                            $stmt->bind_param('ssi', $kelas, $kelas, $id_sekolah);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to update class: ' . $stmt->error]);
                                return;
                            }
                        } else {
                            // Insert new record
                            $stmt = $mysqli->prepare("INSERT INTO kelas (kelas, id_sekolah) VALUES (?, ?)");
                            $stmt->bind_param('si', $kelas, $id_sekolah);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to add class: ' . $stmt->error]);
                                return;
                            }
                        }
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'School not found']);
                        return;
                    }
                }

                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Classes updated/added successfully']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'File is missing or upload error']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleImportMurid($method)
{
    global $mysqli;

    if ($method === 'POST') {
        if (isset($_FILES['file_excel']) && $_FILES['file_excel']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_excel']['tmp_name'];
            $fileName = $_FILES['file_excel']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if ($fileExtension != 'xlsx') {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
                return;
            }

            try {
                $spreadsheet = IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Remove the header row
                $header = array_shift($data);

                // Check if header is correct
                if ($header[0] !== 'nama lengkap' || $header[1] !== 'nip' || $header[2] !== 'alamat' || $header[3] !== 'telp' || $header[4] !== 'email' || $header[5] !== 'kelas') {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Invalid file format']);
                    return;
                }

                // Process each row
                foreach ($data as $row) {
                    $nama_lengkap = $row[0];
                    $nip = $row[1];
                    $alamat = $row[2];
                    $telp = $row[3];
                    $email = $row[4];
                    $kelas = $row[5];

                    // Generate password using MD5 hash of NIP
                    $password = md5($nip);

                    // Fetch the class ID based on the class name
                    $stmt = $mysqli->prepare("SELECT id AS id_kelas FROM kelas WHERE kelas = ?");
                    $stmt->bind_param('s', $kelas);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $kelasData = $result->fetch_assoc();
                        $id_kelas = $kelasData['id_kelas'];

                        // Check if the teacher already exists
                        $stmt = $mysqli->prepare("SELECT id FROM guru WHERE nip = ? AND id_kelas = ?");
                        $stmt->bind_param('si', $nip, $id_kelas);
                        $stmt->execute();
                        $existingGuruResult = $stmt->get_result();

                        if ($existingGuruResult->num_rows > 0) {
                            // Update existing record
                            $stmt = $mysqli->prepare("UPDATE guru SET nama_lengkap = ?, alamat = ?, telp = ?, id_kelas = ?, password = ?, email = ? WHERE nip = ?");
                            $stmt->bind_param('sssiss', $nama_lengkap, $alamat, $telp, $id_kelas, $password, $email, $nip);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to update teacher: ' . $stmt->error]);
                                return;
                            }
                        } else {
                            // Insert new record
                            $stmt = $mysqli->prepare("INSERT INTO guru (nama_lengkap, nip, alamat, telp, id_kelas, password, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param('sssiiss', $nama_lengkap, $nip, $alamat, $telp, $id_kelas, $password, $email);
                            $stmt->execute();

                            if ($stmt->error) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Failed to add teacher: ' . $stmt->error]);
                                return;
                            }
                        }
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Class not found']);
                        return;
                    }
                }

                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Guru updated/added successfully']);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'File is missing or upload error']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditKelas($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $kelas = isset($_POST['kelas']) ? $_POST['kelas'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($id && $kelas && $id_operator) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("UPDATE kelas SET kelas = ?, id_operator = ? WHERE id = ?");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sii', $kelas, $id_operator, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Class updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update class or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditJadwalHafalan($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $id_guru = isset($_POST['id_guru']) ? $_POST['id_guru'] : null;
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
        $tanggal_akhir = isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : null;
        $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : null;

        // Check if all required fields are provided
        if ($id && $id_guru && $tanggal && $tanggal_akhir && $deskripsi) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("UPDATE jadwal_hafalan SET id_guru = ?, tanggal = ?, tanggal_akhir = ?, deskripsi = ? WHERE id = ?");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('ssssi', $id_guru, $tanggal, $tanggal_akhir, $deskripsi, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal hafalan updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update jadwal hafalan or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleEditTahun($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
        $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($id && $tahun && $id_sekolah && $id_operator) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("UPDATE tahun_akademik SET tahun = ?, id_sekolah = ?, id_operator = ? WHERE id = ?");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('siii', $tahun, $id_sekolah, $id_operator, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Tahun updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update tahun or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditBerita($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $judul = isset($_POST['judul']) ? $_POST['judul'] : null;
        $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : null;
        $gambar = isset($_POST['gambar']) ? $_POST['gambar'] : null;
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : null;
        $id_operator = isset($_POST['id_operator']) ? $_POST['id_operator'] : null;

        // Check if all required fields are provided
        if ($id && $judul && $deskripsi && $gambar && $tanggal &&  $id_operator) {
            // Prepare the SQL statement
            $stmt = $mysqli->prepare("UPDATE berita SET judul = ?, deskripsi = ?, gambar = ?, tanggal = ?, id_operator = ? WHERE id = ?");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('ssssii', $judul, $deskripsi, $gambar, $tanggal, $id_operator, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Berita updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update berita or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleEditTahunStatus($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;

        // Check if all required fields are provided
        if ($id && $status !== null && $id_sekolah) { // Ensure status is not null
            // Set a new status for all records with the same id_sekolah
            $resetStmt = $mysqli->prepare("UPDATE tahun_akademik SET status = ? WHERE id_sekolah = ? AND id != ?");
            if ($resetStmt === false) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement for resetting statuses: ' . $mysqli->error]);
                return;
            }
            $newStatusForOthers = '0'; // Set a blank or a different status if needed
            $resetStmt->bind_param('sii', $newStatusForOthers, $id_sekolah, $id);
            $resetStmt->execute();

            // Prepare the SQL statement to update the specific record's status
            $stmt = $mysqli->prepare("UPDATE tahun_akademik SET status = ? WHERE id = ?");
            if ($stmt === false) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement for updating status: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('si', $status, $id); // Bind status and id
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update status or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID, status, and id_sekolah are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}




function handleTambahOperator($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : null;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;

        // Check if all fields are provided
        if ($fullname && $username && $password && $email && $phone_number && $address && $id_sekolah) {
            // Hash the password using md5
            $hashed_password = md5($password);

            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO operator (fullname, username, password, email, phone_number, address, id_sekolah, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('ssssssi', $fullname, $username, $hashed_password, $email, $phone_number, $address, $id_sekolah);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Operator added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add operator']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditOperator($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : null;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $id_sekolah = isset($_POST['id_sekolah']) ? $_POST['id_sekolah'] : null;

        // Check if all required fields are provided
        if ($id && $fullname && $username && $email && $phone_number && $address && $id_sekolah) {
            // Prepare the SQL statement
            if ($password) {
                // If password is provided, hash it and include in the update query
                $hashed_password = md5($password);
                $stmt = $mysqli->prepare("UPDATE operator SET fullname = ?, username = ?, password = ?, email = ?, phone_number = ?, address = ?, id_sekolah = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssii', $fullname, $username, $hashed_password, $email, $phone_number, $address, $id_sekolah, $id);
            } else {
                // If password is not provided, omit it from the update query
                $stmt = $mysqli->prepare("UPDATE operator SET fullname = ?, username = ?, email = ?, phone_number = ?, address = ?, id_sekolah = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssi', $fullname, $username, $email, $phone_number, $address, $id_sekolah, $id);
            }

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Execute the statement
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Operator updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update operator or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahGuru($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : null;
        $nip = isset($_POST['nip']) ? $_POST['nip'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $no_telepon = isset($_POST['no_telepon']) ? $_POST['no_telepon'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $id_kelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : null;

        // Check if all fields are provided
        if ($nama_lengkap && $nip && $password && $alamat && $no_telepon && $email && $id_kelas) {
            // Hash the password using md5
            $hashed_password = md5($password);

            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO guru (nama_lengkap, nip, password, alamat, no_telepon, email, id_kelas, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('ssssssi', $nama_lengkap, $nip, $hashed_password, $alamat, $no_telepon, $email, $id_kelas);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Guru added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add guru']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleTambahMurid($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : null;
        $nis = isset($_POST['nis']) ? $_POST['nis'] : null;
        $nisn = isset($_POST['nisn']) ? $_POST['nisn'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
        $tempat_lahir = isset($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : null;
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $no_telepon = isset($_POST['no_telepon']) ? $_POST['no_telepon'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $wali_murid = isset($_POST['wali_murid']) ? $_POST['wali_murid'] : null;
        $no_telepon_wali_murid = isset($_POST['no_telepon_wali_murid']) ? $_POST['no_telepon_wali_murid'] : null;
        $id_kelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : null;

        // Check if all fields are provided
        if ($nama_lengkap && $nis && $nisn && $password && $tanggal_lahir && $tempat_lahir && $jenis_kelamin && $alamat && $no_telepon && $email && $wali_murid && $no_telepon_wali_murid && $id_kelas) {
            // Hash the password using md5
            $hashed_password = md5($password);

            // Prepare the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO murid (nama_lengkap, nis, nisn, password, tanggal_lahir, tempat_lahir, jenis_kelamin, alamat, no_telepon, email, wali_murid, no_telepon_wali_murid, id_kelas, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Bind parameters and execute the statement
            $stmt->bind_param('sssssssssssss', $nama_lengkap, $nis, $nisn, $hashed_password, $tanggal_lahir, $tempat_lahir, $jenis_kelamin, $alamat, $no_telepon, $email, $wali_murid, $no_telepon_wali_murid, $id_kelas);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Murid added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add murid']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditGuru($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : null;
        $nip = isset($_POST['nip']) ? $_POST['nip'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $no_telepon = isset($_POST['no_telepon']) ? $_POST['no_telepon'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $id_kelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : null;

        // Check if all required fields are provided
        if ($id && $nama_lengkap && $nip && $email && $alamat && $no_telepon && $id_kelas) {
            // Prepare the SQL statement
            if ($password) {
                // If password is provided, hash it and include it in the update query
                $hashed_password = md5($password);
                $stmt = $mysqli->prepare("UPDATE guru SET nama_lengkap = ?, nip = ?, password = ?, alamat = ?, no_telepon = ?, email = ?, id_kelas = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssii', $nama_lengkap, $nip, $hashed_password, $alamat, $no_telepon, $email, $id_kelas, $id);
            } else {
                // If password is not provided, omit it from the update query
                $stmt = $mysqli->prepare("UPDATE guru SET nama_lengkap = ?, nip = ?, alamat = ?, no_telepon = ?, email = ?, id_kelas = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssi', $nama_lengkap, $nip, $alamat, $no_telepon, $email, $id_kelas, $id);
            }

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Execute the statement
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Guru updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update guru or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleEditMurid($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : null;
        $nis = isset($_POST['nis']) ? $_POST['nis'] : null;
        $nisn = isset($_POST['nisn']) ? $_POST['nisn'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
        $tempat_lahir = isset($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : null;
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : null;
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
        $no_telepon = isset($_POST['no_telepon']) ? $_POST['no_telepon'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $wali_murid = isset($_POST['wali_murid']) ? $_POST['wali_murid'] : null;
        $no_telepon_wali_murid = isset($_POST['no_telepon_wali_murid']) ? $_POST['no_telepon_wali_murid'] : null;
        $id_kelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : null;

        // Check if all required fields are provided
        if ($id && $nama_lengkap && $nis && $nisn && $email && $alamat && $tanggal_lahir && $tempat_lahir && $no_telepon && $wali_murid && $no_telepon_wali_murid && $id_kelas) {
            // Prepare the SQL statement
            if ($password) {
                // If password is provided, hash it and include it in the update query
                $hashed_password = md5($password);
                $stmt = $mysqli->prepare("UPDATE murid SET nama_lengkap = ?, nis = ?, nisn = ?, password = ?, tanggal_lahir = ?, tempat_lahir = ?, jenis_kelamin = ?, alamat = ?, no_telepon = ?, email = ?, wali_murid = ?, no_telepon_wali_murid = ?, id_kelas = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('sssssssssssssi', $nama_lengkap, $nis, $nisn, $hashed_password, $tanggal_lahir, $tempat_lahir, $jenis_kelamin, $alamat, $no_telepon, $email, $wali_murid, $no_telepon_wali_murid, $id_kelas, $id);
            } else {
                // If password is not provided, omit it from the update query
                $stmt = $mysqli->prepare("UPDATE murid SET nama_lengkap = ?, nis = ?, nisn = ?, tanggal_lahir = ?, tempat_lahir = ?, jenis_kelamin = ?, alamat = ?, no_telepon = ?, email = ?, wali_murid = ?, no_telepon_wali_murid = ?, id_kelas = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssssssss', $nama_lengkap, $nis, $nisn, $tanggal_lahir, $tempat_lahir, $jenis_kelamin, $alamat, $no_telepon, $email, $wali_murid, $no_telepon_wali_murid, $id_kelas, $id);
            }

            // Check if the prepare() was successful
            if ($stmt === false) {
                // Output error and stop execution
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Execute the statement
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Murid updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update murid or no changes made']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All required fields are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleEditAdmin($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Retrieve data from form input
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;

        // Check if all required fields are provided
        if ($id && $username && $full_name && $email && $address && $phone_number) {
            // Prepare the SQL statement
            if ($password) {
                // Include password in the update query
                $stmt = $mysqli->prepare("UPDATE admin SET username = ?, full_name = ?, password = ?, address = ?, phone_number = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('ssssssi', $username, $full_name, $password, $address, $phone_number, $email, $id);
            } else {
                // If password is not provided, omit it from the update query
                $stmt = $mysqli->prepare("UPDATE admin SET username = ?, full_name = ?, address = ?, phone_number = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bind_param('sssssi', $username, $full_name, $address, $phone_number, $email, $id);
            }

            // Check if the prepare() was successful
            if ($stmt === false) {
                echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
                return;
            }

            // Execute the statement
            $stmt->execute();

            // Check the number of affected rows
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Admin updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update admin or no changes made']);
            }

            // Close the statement
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required except password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleHapusGuru($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM guru WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Guru deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusBerita($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM berita WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Berita deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusJadwalHafalan($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM jadwal_hafalan WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Jadwal Hafalan deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusSekolah($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM sekolah WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'School deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusOperator($method)
{
    global $mysqli;

    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id !== null) {
            // Siapkan query untuk menghapus operator berdasarkan ID
            $stmt = $mysqli->prepare("DELETE FROM operator WHERE id = ?");

            if ($stmt === false) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
                return;
            }

            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Operator deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleHapusKelas($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM kelas WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Kelas deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusMurid($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM murid WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Murid deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleHapusTahun($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // Mendapatkan data dari body request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id) {
            $stmt = $mysqli->prepare("DELETE FROM tahun_akademik WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Tahun deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found with the given ID']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}

function handleLogin($method)
{
    global $mysqli;
    if ($method === 'POST') {
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        if ($username && $password) {
            // Prepare the statement to retrieve user data
            $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Check if user exists and password matches without hashing
            if ($user && $password === $user['password']) {
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'user' => $user]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}


function handleLoginApp($method)
{
    global $mysqli;
    if ($method === 'POST') {
        // For form submissions, use $_POST to get data
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $nip = isset($_POST['nip']) ? $_POST['nip'] : null; // For guru login
        $nis = isset($_POST['nis']) ? $_POST['nis'] : null; // For murid login
        $nisn = isset($_POST['nisn']) ? $_POST['nisn'] : null; // For walimurid login

        if (($username || $nip || $nis || $nisn) && $password) {
            // Check login for operator using username
            if ($username) {
                $stmt = $mysqli->prepare("SELECT * FROM operator WHERE username = ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                // Hash the provided password with md5
                $hashedPassword = md5($password);

                // Verify the hashed password with the stored password
                if ($user && $hashedPassword === $user['password']) {
                    //echo as json and add user data
                    echo json_encode(['status' => 'success', 'message' => 'Login successful', 'user' => $user]);
                    return;
                }
            }

            // Check login for guru using NIP
            if ($nip) {
                $stmt = $mysqli->prepare("SELECT * FROM guru WHERE nip = ?");
                $stmt->bind_param('s', $nip);
                $stmt->execute();
                $result = $stmt->get_result();
                $guru = $result->fetch_assoc();

                // Hash the provided password with md5
                $hashedPassword = md5($password);

                // Verify the hashed password with the stored password
                if ($guru && $hashedPassword === $guru['password']) {
                    echo json_encode(['status' => 'success', 'message' => 'Login successful as guru', 'user' => $guru]);
                    return;
                }
            }

            // Check login for murid using NIS
            if ($nis) {
                $stmt = $mysqli->prepare("SELECT * FROM murid WHERE nis = ?");
                $stmt->bind_param('s', $nis);
                $stmt->execute();
                $result = $stmt->get_result();
                $murid = $result->fetch_assoc();

                // Hash the provided password with md5
                $hashedPassword = md5($password);

                // Verify the hashed password with the stored password
                if ($murid && $hashedPassword === $murid['password']) {
                    echo json_encode(['status' => 'success', 'message' => 'Login successful as siswa', 'user' => $murid]);
                    return;
                }
            }

            // Check login for walimurid using NISN from the murid table
            if ($nisn) {
                $stmt = $mysqli->prepare("SELECT * FROM murid WHERE nisn = ?");
                $stmt->bind_param('s', $nisn);
                $stmt->execute();
                $result = $stmt->get_result();
                $murid = $result->fetch_assoc();

                // Verify the nisn as both username and password without hashing
                if ($murid && $password === $murid['nisn']) {
                    echo json_encode(['status' => 'success', 'message' => 'Login successful as walimurid', 'user' => $murid]);
                    return;
                }
            }

            // Unified error message if no login is successful
            echo json_encode(['status' => 'error', 'message' => 'Invalid username/NIP/NIS/NISN or password']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Username/NIP/NIS/NISN and password are required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
}
