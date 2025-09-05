<?php
session_start();
include 'config.php';

// Check if user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
    $_SESSION['error'] = 'You need to be logged in as a property owner to add a property.';
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - RentalNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .day-selector {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .day-selector:hover {
            transform: translateY(-2px);
        }
        
        .day-selector.selected {
            background-color: #4361ee;
            color: white;
        }
        
        .time-slot {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }
        
        .time-slot:hover {
            border-color: #4361ee;
            background-color: #f8fafc;
        }
        
        .remove-time-slot {
            color: #e53e3e;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .remove-time-slot:hover {
            color: #c53030;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white py-4">
                        <h3 class="text-center mb-0">Add New Property</h3>
                        <p class="text-center mb-0 opacity-75">Fill in the details of your property</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="add_property_process.php" method="POST" enctype="multipart/form-data">
                            <h4 class="fw-bold mb-4">Property Information</h4>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="district" class="form-label fw-bold">District</label>
                                        <select class="form-select" id="district" name="district" required>
                                            <option value="">Select District</option>
                                            <option value="Dhaka">Dhaka</option>
                                            <option value="Chattogram">Chattogram</option>
                                            <option value="Sylhet">Sylhet</option>
                                            <option value="Khulna">Khulna</option>
                                            <option value="Rajshahi">Rajshahi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="street" class="form-label fw-bold">Street</label>
                                        <input type="text" class="form-control" id="street" name="street" required 
                                               placeholder="Enter street name">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="area" class="form-label fw-bold">Area</label>
                                        <input type="text" class="form-control" id="area" name="area" required 
                                               placeholder="Enter area name (e.g., Badda, Gulshan)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="members_count" class="form-label fw-bold">Maximum Occupancy</label>
                                        <input type="number" class="form-control" id="members_count" name="members_count" required 
                                               min="1" max="10" placeholder="Number of people">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label fw-bold">Monthly Price (BDT)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" class="form-control" id="price" name="price" required 
                                                   min="1000" placeholder="Enter monthly rent">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="availability" class="form-label fw-bold">Availability</label>
                                        <select class="form-select" id="availability" name="availability" required>
                                            <option value="1">Available Now</option>
                                            <option value="0">Not Available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required 
                                          placeholder="Describe your property, amenities, and any special features..."></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Property Images</label>
                                <input class="form-control" type="file" name="images[]" multiple accept="image/*">
                                <div class="form-text">You can upload up to 5 images of your property</div>
                            </div>
                            
                            <!-- Visit Availability Section -->
                            <h4 class="fw-bold mb-4 mt-5">Viewing Availability</h4>
                            
                            <div class="mb-4">
                                <p class="text-muted mb-3">Set your preferred days and times for property viewings:</p>
                                
                                <!-- Day Selector -->
                                <div class="d-flex gap-2 mb-3">
                                    <?php 
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    for ($i = 0; $i < 7; $i++): 
                                    ?>
                                        <div class="day-selector text-center py-2 px-3 rounded-3" 
                                             data-day="<?= $i ?>" style="width: 60px;">
                                            <div class="fw-bold"><?= substr($days[$i], 0, 3) ?></div>
                                            <div class="small"><?= $i === 0 ? 'Sun' : ($i === 6 ? 'Sat' : '') ?></div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                
                                <!-- Time Slot Form -->
                                <div class="card p-4 mb-4 bg-light" id="timeSlotForm" style="display: none;">
                                    <input type="hidden" id="selectedDay" name="selected_day">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">Start Time</label>
                                            <input type="time" class="form-control" id="startTime" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">End Time</label>
                                            <input type="time" class="form-control" id="endTime" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary w-100" id="addTimeSlot">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Added Time Slots -->
                                <div id="addedTimeSlots">
                                    <!-- Time slots will be added here dynamically -->
                                </div>
                                
                                <input type="hidden" id="visitAvailabilityData" name="visit_availability" value="">
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3">
                                    <i class="bi bi-house-add me-2"></i>Add Property
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Day selector functionality
            const daySelectors = document.querySelectorAll('.day-selector');
            const timeSlotForm = document.getElementById('timeSlotForm');
            const selectedDayInput = document.getElementById('selectedDay');
            
            daySelectors.forEach(selector => {
                selector.addEventListener('click', function() {
                    // Reset all selections
                    daySelectors.forEach(s => s.classList.remove('selected'));
                    
                    // Select current day
                    this.classList.add('selected');
                    selectedDayInput.value = this.getAttribute('data-day');
                    timeSlotForm.style.display = 'block';
                });
            });
            
            // Add time slot functionality
            const addTimeSlotBtn = document.getElementById('addTimeSlot');
            const addedTimeSlots = document.getElementById('addedTimeSlots');
            const visitAvailabilityData = document.getElementById('visitAvailabilityData');
            
            addTimeSlotBtn.addEventListener('click', function() {
                const selectedDay = selectedDayInput.value;
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                
                if (!selectedDay || !startTime || !endTime) {
                    alert('Please select a day and enter both start and end times.');
                    return;
                }
                
                // Validate time
                if (startTime >= endTime) {
                    alert('End time must be after start time.');
                    return;
                }
                
                // Get day name for display
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const dayName = days[selectedDay];
                
                // Create time slot element
                const timeSlot = document.createElement('div');
                timeSlot.className = 'time-slot d-flex justify-content-between align-items-center';
                timeSlot.innerHTML = `
                    <div>
                        <strong>${dayName}</strong>: ${formatTime(startTime)} - ${formatTime(endTime)}
                    </div>
                    <div class="remove-time-slot" data-day="${selectedDay}" 
                         data-start="${startTime}" data-end="${endTime}">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                `;
                
                // Add to DOM
                addedTimeSlots.appendChild(timeSlot);
                
                // Update hidden input with JSON data
                updateAvailabilityData();
                
                // Reset form
                document.getElementById('startTime').value = '';
                document.getElementById('endTime').value = '';
            });
            
            // Remove time slot functionality
            addedTimeSlots.addEventListener('click', function(e) {
                if (e.target.closest('.remove-time-slot')) {
                    const slot = e.target.closest('.time-slot');
                    slot.remove();
                    updateAvailabilityData();
                }
            });
            
            // Format time for display (e.g., "09:00" -> "9:00 AM")
            function formatTime(time) {
                const [hours, minutes] = time.split(':');
                const period = hours >= 12 ? 'PM' : 'AM';
                const formattedHours = hours % 12 || 12;
                return `${formattedHours}:${minutes} ${period}`;
            }
            
            // Update the hidden input with availability data
            function updateAvailabilityData() {
                const timeSlots = [];
                document.querySelectorAll('.time-slot').forEach(slot => {
                    const removeBtn = slot.querySelector('.remove-time-slot');
                    timeSlots.push({
                        day: removeBtn.getAttribute('data-day'),
                        start: removeBtn.getAttribute('data-start'),
                        end: removeBtn.getAttribute('data-end')
                    });
                });
                
                visitAvailabilityData.value = JSON.stringify(timeSlots);
            }
        });
    </script>
</body>
</html>