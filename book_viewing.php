<?php
session_start();
include 'config.php';

// Check if user is logged in and is a renter
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'renter') {
    $_SESSION['error'] = 'You need to be logged in as a renter to book a viewing.';
    header('Location: login.php');
    exit();
}

// Get property ID from URL
if (!isset($_GET['f_id'])) {
    $_SESSION['error'] = 'Property ID is required.';
    header('Location: search.php');
    exit();
}

$f_id = $_GET['f_id'];

// Get property details
$stmt = $pdo->prepare("SELECT f.*, o.name as owner_name 
                      FROM flat f 
                      JOIN owner o ON f.owner_id = o.owner_id 
                      WHERE f.f_id = ?");
$stmt->execute([$f_id]);
$property = $stmt->fetch();

if (!$property) {
    $_SESSION['error'] = 'Property not found.';
    header('Location: search.php');
    exit();
}

// Get visit availability for this property
$stmt = $pdo->prepare("SELECT * FROM visit_availability 
                      WHERE f_id = ? 
                      ORDER BY day_of_week, start_time");
$stmt->execute([$f_id]);
$visitAvailability = $stmt->fetchAll();

if (count($visitAvailability) === 0) {
    $_SESSION['error'] = 'This property does not have viewing availability set.';
    header('Location: property_details.php?f_id=' . $f_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Viewing - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .calendar-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .calendar-day-header {
            text-align: center;
            font-weight: bold;
            padding: 5px;
            background-color: #f1f5f9;
            border-radius: 4px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-date {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            min-height: 80px;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .calendar-date:hover {
            background-color: #edf2f7;
            border-color: #cbd5e0;
        }
        
        .calendar-date.today {
            background-color: #dbeafe;
            border-color: #93c5fd;
            font-weight: bold;
        }
        
        .calendar-date.unavailable {
            background-color: #fdecea;
            border-color: #fbb4b4;
            cursor: not-allowed;
        }
        
        .calendar-date .date-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .time-slot {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 5px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        
        .time-slot:hover {
            background-color: #ebf5ff;
            border-color: #93c5fd;
        }
        
        .time-slot.booked {
            background-color: #fee2e2;
            border-color: #fecaca;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white py-4">
                        <h3 class="text-center mb-0">Schedule a Viewing</h3>
                        <p class="text-center mb-0 opacity-75">Select a date and time to view <?= htmlspecialchars($property['area']) ?></p>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-house fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($property['area']) ?></h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($property['district']) ?>, <?= htmlspecialchars($property['street']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <h4 class="h5 fw-bold mb-3 d-flex align-items-center">
                                <i class="bi bi-calendar-check me-2 text-primary"></i> Select a Date
                            </h4>
                            
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button class="btn btn-outline-primary" id="prevMonth">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <h5 id="currentMonth">August 2025</h5>
                                    <button class="btn btn-outline-primary" id="nextMonth">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                                
                                <div class="calendar-days">
                                    <div class="calendar-day-header">Sun</div>
                                    <div class="calendar-day-header">Mon</div>
                                    <div class="calendar-day-header">Tue</div>
                                    <div class="calendar-day-header">Wed</div>
                                    <div class="calendar-day-header">Thu</div>
                                    <div class="calendar-day-header">Fri</div>
                                    <div class="calendar-day-header">Sat</div>
                                </div>
                                
                                <div class="calendar-grid" id="calendarGrid">
                                    <!-- Calendar will be generated by JavaScript -->
                                </div>
                            </div>
                            
                            <div class="mt-4" id="timeSlotsContainer" style="display: none;">
                                <h5 class="fw-bold mb-3">Available Time Slots for <span id="selectedDateDisplay"></span></h5>
                                <div id="timeSlotsList">
                                    <!-- Time slots will be loaded here -->
                                </div>
                            </div>
                            
                            <div id="bookingConfirmation" class="alert alert-success mt-4" style="display: none;">
                                <h5 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Appointment Booked!</h5>
                                <p>Your viewing appointment has been scheduled. The property owner will contact you with confirmation.</p>
                                <hr>
                                <p class="mb-0">You can manage your appointments in your <a href="my_bookings.php" class="alert-link">My Bookings</a> section.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();
            
            // Function to generate calendar
            function generateCalendar(month, year) {
                const calendarGrid = document.getElementById('calendarGrid');
                calendarGrid.innerHTML = '';
                
                // Get first day of month
                const firstDay = new Date(year, month, 1);
                const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
                
                // Get total days in month
                const lastDay = new Date(year, month + 1, 0);
                const totalDays = lastDay.getDate();
                
                // Fill in previous month's days (grayed out)
                for (let i = 0; i < startingDay; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date disabled';
                    dateCell.innerHTML = `<span class="date-number">${new Date(year, month, 0 - i).getDate()}</span>`;
                    calendarGrid.appendChild(dateCell);
                }
                
                // Fill in current month's days
                for (let i = 1; i <= totalDays; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date';
                    dateCell.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    
                    // Check if this is today
                    if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dateCell.classList.add('today');
                    }
                    
                    // Check if date is in the past
                    const dateToCheck = new Date(year, month, i);
                    if (dateToCheck < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                        dateCell.classList.add('unavailable');
                    }
                    
                    dateCell.innerHTML = `<span class="date-number">${i}</span>`;
                    
                    // Add click event to show time slots
                    dateCell.addEventListener('click', function() {
                        if (this.classList.contains('unavailable')) return;
                        
                        document.querySelectorAll('.calendar-date').forEach(d => d.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        const selectedDate = this.dataset.date;
                        document.getElementById('selectedDateDisplay').textContent = formatDate(selectedDate);
                        
                        // Show time slots container
                        document.getElementById('timeSlotsContainer').style.display = 'block';
                        
                        // Load available time slots for this date
                        loadTimeSlots(selectedDate);
                    });
                    
                    calendarGrid.appendChild(dateCell);
                }
                
                // Fill in next month's days to complete the grid
                const daysAdded = startingDay + totalDays;
                const remainingCells = 42 - daysAdded; // 6 rows of 7 days
                
                for (let i = 1; i <= remainingCells; i++) {
                    const dateCell = document.createElement('div');
                    dateCell.className = 'calendar-date disabled';
                    dateCell.innerHTML = `<span class="date-number">${i}</span>`;
                    calendarGrid.appendChild(dateCell);
                }
                
                // Update month display
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                   "July", "August", "September", "October", "November", "December"];
                document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
            }
            
            // Function to load time slots for a specific date
            function loadTimeSlots(date) {
                const timeSlotsList = document.getElementById('timeSlotsList');
                timeSlotsList.innerHTML = '<div class="text-center py-3">Loading available time slots...</div>';
                
                // In a real application, this would be an AJAX call to the server
                // For this example, we'll simulate it with a timeout
                setTimeout(() => {
                    const dayOfWeek = new Date(date).getDay(); // 0 = Sunday, 1 = Monday, etc.
                    
                    // Filter availability for this day of week
                    const availability = <?= json_encode($visitAvailability) ?>;
                    const dayAvailability = availability.filter(slot => slot.day_of_week == dayOfWeek);
                    
                    if (dayAvailability.length === 0) {
                        timeSlotsList.innerHTML = '<div class="alert alert-info">No viewing availability set for this day.</div>';
                        return;
                    }
                    
                    let slotsHTML = '';
                    
                    // Generate time slots based on availability
                    dayAvailability.forEach(slot => {
                        const startTime = new Date(`1970-01-01T${slot.start_time}`);
                        const endTime = new Date(`1970-01-01T${slot.end_time}`);
                        
                        // Generate 30-minute intervals
                        let currentTime = new Date(startTime);
                        while (currentTime < endTime) {
                            const nextTime = new Date(currentTime);
                            nextTime.setMinutes(nextTime.getMinutes() + 30);
                            
                            if (nextTime <= endTime) {
                                const formattedTime = `${formatTime(currentTime)} - ${formatTime(nextTime)}`;
                                slotsHTML += `
                                    <div class="time-slot" 
                                         data-date="${date}" 
                                         data-start="${formatTimeForDB(currentTime)}"
                                         data-end="${formatTimeForDB(nextTime)}">
                                        ${formattedTime}
                                    </div>
                                `;
                            }
                            
                            currentTime = nextTime;
                        }
                    });
                    
                    if (slotsHTML === '') {
                        timeSlotsList.innerHTML = '<div class="alert alert-info">No available time slots for this date.</div>';
                    } else {
                        timeSlotsList.innerHTML = slotsHTML;
                        
                        // Add click events to time slots
                        document.querySelectorAll('.time-slot').forEach(slot => {
                            slot.addEventListener('click', function() {
                                const date = this.dataset.date;
                                const startTime = this.dataset.start;
                                const endTime = this.dataset.end;
                                
                                // In a real application, this would be an AJAX call to book the appointment
                                // For this example, we'll just show a confirmation
                                document.getElementById('bookingConfirmation').style.display = 'block';
                                
                                // Scroll to confirmation
                                document.getElementById('bookingConfirmation').scrollIntoView({
                                    behavior: 'smooth'
                                });
                            });
                        });
                    }
                }, 300);
            }
            
            // Helper function to format time
            function formatTime(date) {
                return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            // Helper function to format time for database
            function formatTimeForDB(date) {
                return date.toTimeString().substring(0, 5);
            }
            
            // Helper function to format date for display
            function formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
            
            // Initialize calendar
            generateCalendar(currentMonth, currentYear);
            
            // Previous month button
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentMonth, currentYear);
            });
            
            // Next month button
            document.getElementById('nextMonth').addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentMonth, currentYear);
            });
        });
    </script>
</body>
</html>