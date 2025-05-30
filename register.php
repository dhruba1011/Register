<?php
include 'connection.php';

// 1. Fetch dropdowns first
$sessions    = [];
$courses     = [];
$classDays   = [];
$classTimes  = [];
$genders     = [];
$casts       = [];
$last_exams  = [];

$res = $conn->query("SELECT DISTINCT session FROM settings WHERE session<>''");
while ($r = $res->fetch_assoc()) $sessions[] = $r['session'];

$res = $conn->query("SELECT DISTINCT course FROM settings WHERE course<>''");
while ($r = $res->fetch_assoc()) $courses[] = $r['course'];

$batchCols = ['cita','dita','adita','cdta','ddta','cfas','dfas','adfas','cdtp','ddtp'];
$courseBatches = [];
foreach ($batchCols as $col) {
    $courseBatches[$col] = [];
    $res = $conn->query("SELECT DISTINCT $col FROM settings WHERE $col<>''");
    while ($r = $res->fetch_assoc()) {
        foreach (array_map('trim', explode(',', $r[$col])) as $batch) {
            if ($batch !== '' && !in_array($batch, $courseBatches[$col], true)) {
                $courseBatches[$col][] = $batch;
            }
        }
    }
}

// 2. Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form_session    = mysqli_real_escape_string($conn, trim($_POST['session']));
    $form_course     = strtoupper(mysqli_real_escape_string($conn, trim($_POST['course'])));
    $batch           = mysqli_real_escape_string($conn, trim($_POST['batch']));
    $regno           = mysqli_real_escape_string($conn, trim($_POST['regno']));
    $class_days      = mysqli_real_escape_string($conn, trim($_POST['class_days']));
    $class_times     = mysqli_real_escape_string($conn, trim($_POST['class_times']));
    $sname           = strtoupper(mysqli_real_escape_string($conn, trim($_POST['sname'])));
    $fname           = strtoupper(mysqli_real_escape_string($conn, trim($_POST['fname'])));
    $dob             = mysqli_real_escape_string($conn, trim($_POST['dob']));
    $gender          = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $cast            = mysqli_real_escape_string($conn, trim($_POST['cast']));
    $phone1          = mysqli_real_escape_string($conn, trim($_POST['phone1']));
    $phone2          = mysqli_real_escape_string($conn, trim($_POST['phone2']));
    $last_exam       = mysqli_real_escape_string($conn, trim($_POST['last_exam']));
    $mp_mark         = $_POST['mp_mark'] !== '' ? intval($_POST['mp_mark']) : "NULL";
    $hs_mark         = $_POST['hs_mark'] !== '' ? intval($_POST['hs_mark']) : "NULL";

    // Validation for phone numbers being different
    if ($phone1 === $phone2) {
        echo "Error: Phone 1 and Phone 2 cannot be the same.";
        exit;
    }

    // If last_exam is MP, HS mark should be null
    if ($last_exam === "MP") {
        $hs_mark = "NULL";
    }

    $sql = "INSERT INTO registration
      (session, course, batch, regno, class_days, class_times,
       sname, fname, dob, gender, cast, phone1, phone2, last_exam, mp_mark, hs_mark)
     VALUES
      ('$form_session','$form_course','$batch','$regno','$class_days','$class_times',
       '$sname','$fname','$dob','$gender','$cast','$phone1','$phone2','$last_exam', $mp_mark, $hs_mark)";

    if ($conn->query($sql)) {
        session_start();
        $_SESSION['reg_details'] = compact('form_session', 'form_course', 'batch', 'regno', 'class_days', 'class_times', 'sname', 'fname', 'dob', 'gender', 'cast', 'phone1', 'phone2', 'last_exam', 'mp_mark', 'hs_mark');
        header('Location: success.php');
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Registration Form</title>
<link rel="stylesheet" href="style_regis.css" />
<style>
  /* Highlight invalid fields */
  input.invalid, select.invalid {
    border: 2px solid red;
  }
  /* Hide tabs by default */
  .tab {
    display: none;
  }
  /* Show active step */
  .step.active {
    opacity: 1;
  }
</style>
</head>
<body>

<h2 style="text-align:center;">Student Registration</h2>

<form id="regForm" method="post" action="">
  <!-- Step 1: Course Info -->
  <div class="tab">
    <h3>Course Info:</h3>
    <label for="session">Session</label>
    <select name="session" id="session" required>
      <option value="">Select Session</option>
      <?php foreach($sessions as $c) echo '<option>'.htmlspecialchars($c).'</option>'; ?>
    </select>

    <label for="course">Course</label>
    <select name="course" id="course" required>
      <option value="">Select Course</option>
      <?php foreach($courses as $c) echo '<option value="'.htmlspecialchars(strtolower($c)).'">'.htmlspecialchars($c).'</option>'; ?>
    </select>

    <label for="batch">Batch</label>
    <select name="batch" id="batch">
      <option value="">Select Batch</option>
    </select>

    <label for="regno">Registration No</label>
    <input type="text" id="regno" name="regno" required value="YS-HBA/" oninput="updateRegno()" />
    <div id="error-message" style="color: red; display: none;">Invalid format. The total length must be 22 characters.</div>

    <label for="class_days">Class Days</label>
    <select name="class_days" id="class_days" required>
      <option value="">Select Days</option>
      <option value="MON-THR">MON-THR</option>
      <option value="TEU-FRI">TEU-FRI</option>
      <option value="WED-SAT">WED-SAT</option>
    </select>

    <label for="class_times">Class Time</label>
    <select name="class_times" id="class_times" required>
      <option value="">Select Time</option>
      <option value="8AM-10AM">8AM-10AM</option>
      <option value="10AM-12PM">10AM-12PM</option>
      <option value="12PM-2PM">12PM-2PM</option>
      <option value="2PM-4PM">2PM-4PM</option>
      <option value="4PM-6PM">4PM-6PM</option>
      <option value="6PM-8PM">6PM-8PM</option>
    </select>
  </div>

  <!-- Step 2: Personal Info -->
  <div class="tab">
    <h3>Personal Info:</h3>
    <label>Student Name</label>
    <input type="text" name="sname" required oninput="this.value = this.value.toUpperCase()" />

    <label>Father’s Name</label>
    <input type="text" name="fname" required oninput="this.value = this.value.toUpperCase()" />

    <label for="dob">Date of Birth</label>
    <input type="date" name="dob" id="dob" required />

    <label for="gender">Gender</label>
    <select name="gender" id="gender" required>
      <option value="">Select Gender</option>
      <option value="MALE">Male</option>
      <option value="FEMALE">Female</option>
    </select>

    <label for="cast">Cast</label>
    <select name="cast" id="cast" required>
      <option value="">Select Cast</option>
      <option value="GENERAL">GENERAL</option>
      <option value="SC">SC</option>
      <option value="ST">ST</option>
      <option value="OBC">OBC</option>
    </select>

    <label for="phone1">Phone 1:</label>
    <input
      type="text"
      id="phone1"
      name="phone1"
      pattern="\d{10}"
      maxlength="10"
      minlength="10"
      oninput="this.value = this.value.replace(/\D/g, '')"
      required
    />

    <label for="phone2">Phone 2:</label>
    <input
      type="text"
      id="phone2"
      name="phone2"
      pattern="\d{10}"
      maxlength="10"
      minlength="10"
      oninput="this.value = this.value.replace(/\D/g, '')"
      required
    />
  </div>

  <!-- Step 3: Education Info -->
  <div class="tab">
    <h3>Education Info:</h3>
    <label for="last_exam">Last Exam</label>
    <select name="last_exam" id="last_exam" required>
      <option value="">Select Last Exam</option>
      <option value="MP">MP</option>
      <option value="HS">HS</option>
      <option value="GRADUATE">GRADUATE</option>
      <option value="POST-GRADUATE">POST-GRADUATE</option>
    </select>

    <label for="mp_mark">MP Mark</label>
    <input type="number" name="mp_mark" id="mp_mark" min="0" step="1" />

    <label for="hs_mark" id="hs_mark_label">HS Mark</label>
    <input type="number" name="hs_mark" id="hs_mark" min="0" step="1" />
  </div>

  <!-- Navigation Buttons -->
  <div class="button-row" style="margin-top: 20px;">
    <button type="button" class="prev" onclick="nextPrev(-1)">Previous</button>
    <button type="button" class="next" onclick="nextPrev(1)">Next</button>
  </div>

  <div style="text-align:center;margin-top:20px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>

<script>
const courseBatches = <?php echo json_encode($courseBatches); ?>;
const course = document.getElementById('course');
const batch = document.getElementById('batch');
const last_exam = document.getElementById('last_exam');
const hs_mark = document.getElementById('hs_mark');
const hs_mark_label = document.getElementById('hs_mark_label');

course.addEventListener('change', function () {
  const selected = course.value.toLowerCase();
  batch.innerHTML = '<option value="">Select Batch</option>';
  if (courseBatches[selected]) {
    courseBatches[selected].forEach((b) => {
      const opt = document.createElement('option');
      opt.value = b;
      opt.innerText = b;
      batch.appendChild(opt);
    });
  }
});

// Update registration number and check format
function updateRegno() {
  const prefix = "YS-HBA/";
  const userInput = document.getElementById('regno').value;
  const totalLength = 22; // The total required length of the registration number

  // Check if the user input starts with the prefix and update it
  if (userInput.startsWith(prefix)) {
    const fullRegno = prefix + userInput.substring(prefix.length);

    if (fullRegno.length === totalLength) {
      document.getElementById('error-message').style.display = "none";
      document.getElementById('regno').classList.remove('invalid');
    } else {
      document.getElementById('error-message').style.display = "inline";
      document.getElementById('regno').classList.add('invalid');
    }
  } else {
    // Force prefix
    document.getElementById('regno').value = prefix;
    document.getElementById('error-message').style.display = "inline";
    document.getElementById('regno').classList.add('invalid');
  }
}

function validatePhoneNumbers() {
  const phone1 = document.getElementById('phone1');
  const phone2 = document.getElementById('phone2');

  if (phone1.value && phone2.value && phone1.value === phone2.value) {
    phone2.classList.add('invalid');
    alert("Phone 1 and Phone 2 cannot be the same.");
    return false;
  }
  phone2.classList.remove('invalid');
  return true;
}

last_exam.addEventListener('change', () => {
  if (last_exam.value === 'MP') {
    hs_mark.style.display = 'none';
    hs_mark_label.style.display = 'none';
    hs_mark.value = '';
  } else {
    hs_mark.style.display = 'inline';
    hs_mark_label.style.display = 'inline';
  }
});

// Show/hide HS Mark on page load
document.addEventListener("DOMContentLoaded", () => {
  const evt = new Event('change');
  last_exam.dispatchEvent(evt);
});

// MULTI STEP FORM LOGIC
var currentTab = 0;
showTab(currentTab);

function showTab(n) {
  let tabs = document.getElementsByClassName("tab");
  tabs[n].style.display = "block";

  // Disable Previous button on first tab
  document.querySelector(".prev").style.display = n === 0 ? "none" : "inline";

  // Change Next button text on last tab
  document.querySelector(".next").innerHTML = (n === (tabs.length - 1)) ? "Submit" : "Next";

  // Update step indicators
  fixStepIndicator(n);
}

function nextPrev(n) {
  let tabs = document.getElementsByClassName("tab");

  // Validate before going next
  if (n === 1 && !validateForm()) return false;

  // Special phone validation on step 2 (index 1)
  if (currentTab === 1 && n === 1) {
    if (!validatePhoneNumbers()) return false;
  }

  // Hide current tab
  tabs[currentTab].style.display = "none";

  currentTab += n;

  // If submit on last step
  if (currentTab >= tabs.length) {
    document.getElementById("regForm").submit();
    return false;
  }

  showTab(currentTab);
}

function validateForm() {
  let valid = true;
  let tabs = document.getElementsByClassName("tab");
  let inputs = tabs[currentTab].querySelectorAll("input, select");

  inputs.forEach((input) => {
    if (input.hasAttribute("required")) {
      if (!input.value || input.value.trim() === "") {
        input.classList.add("invalid");
        valid = false;
      } else {
        // Specific validation for registration number format on step 0
        if (input.id === "regno") {
          const regVal = input.value.trim();
          const prefix = "YS-HBA/";
          if (!regVal.startsWith(prefix) || regVal.length !== 22) {
            input.classList.add("invalid");
            valid = false;
          } else {
            input.classList.remove("invalid");
          }
        } else {
          input.classList.remove("invalid");
        }
      }
    }
  });

  return valid;
}

function fixStepIndicator(n) {
  let steps = document.getElementsByClassName("step");
  for (let i = 0; i < steps.length; i++) {
    steps[i].className = steps[i].className.replace(" active", "");
  }
  steps[n].className += " active";
}
</script>

</body>
</html>
