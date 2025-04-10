@extends('layouts.dashboard')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom Animation for Fade and Slide-in */
        @keyframes fade-slide-in {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-slide {
            animation: fade-slide-in 0.6s ease-out forwards;
        }

        /* Card Styling */
        .card {
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            border-radius: 0.5rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Trend arrow styles */
        .trend-up {
            color: #22c55e; /* Tailwind's green-500 */
        }

        .trend-down {
            color: #ef4444; /* Tailwind's red-500 */
        }

        .trend-arrow {
            font-size: 1rem;
            margin-right: 0.25rem;
        }

        /* Header Styling */
        .dashboard-header {
            background-color: #f9fafb; /* Tailwind's gray-50 */
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #374151; /* Tailwind's gray-700 */
        }

        .header-actions button {
            background-color: #3b82f6; /* Tailwind's blue-500 */
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s ease-in-out;
            border: none;
            cursor: pointer;
            outline: none;
        }

        .header-actions button:hover {
            background-color: #2563eb; /* Tailwind's blue-700 */
        }

        .notification-icon {
            position: relative;
            margin-left: 1.5rem;
        }

        .notification-badge {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background-color: #dc2626; /* Tailwind's red-600 */
            color: white;
            border-radius: 9999px;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            margin-left: 1.5rem;
            object-fit: cover;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 250px;
        }

        /* Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
        }

        .data-table th, .data-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            background-color: #f9fafb; /* Tailwind's gray-50 */
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #6b7280; /* Tailwind's gray-500 */
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f3f4f6; /* Tailwind's gray-100 */
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-approved {
            background-color: #d1fae5; /* Tailwind's green-100 */
            color: #10b981; /* Tailwind's green-500 */
        }

        .status-rejected {
            background-color: #fee2e2; /* Tailwind's red-100 */
            color: #ef4444; /* Tailwind's red-500 */
        }

        .status-pending {
            background-color: #fef08a; /* Tailwind's yellow-100 */
            color: #d97706; /* Tailwind's yellow-600 */
        }

        .table-actions a {
            margin-right: 0.5rem;
            color: #6b7280; /* Tailwind's gray-500 */
            transition: color 0.2s ease-in-out;
        }

        .table-actions a:hover {
            color: #374151; /* Tailwind's gray-700 */
        }

        /* Filter Section */
        .filter-section {
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.07);
            border-radius: 0.5rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-section label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151; /* Tailwind's gray-700 */
            font-size: 0.875rem;
        }

        .filter-section select {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #d1d5db; /* Tailwind's gray-300 */
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #4b5563; /* Tailwind's gray-600 */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
        }

        .filter-section select:focus {
            outline: none;
            border-color: #3b82f6; /* Tailwind's blue-500 */
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25); /* Tailwind's blue-200 with opacity */
        }

        /* Three Column Section */
        .three-column-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .three-column-section .card {
            /* The card styles are already defined above */
        }

        @media (max-width: 768px) {
            .three-column-section {
                grid-template-columns: 1fr; /* Stack on smaller screens */
            }
        }
    </style>

    <div class="dashboard-header animate-fade-slide">
        <div><div style="text-align: center;">  <h1 class="header-title">Project Management Dashboard</h1></div>

            <p class="text-sm text-gray-500">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
{{--        <div class="header-actions">--}}
{{--            <button><i class="fas fa-plus mr-2"></i> Add New Project</button>--}}
{{--            <div class="notification-icon">--}}
{{--                <button class="focus:outline-none">--}}
{{--                    <i class="fas fa-bell text-xl text-gray-600 hover:text-gray-800"></i>--}}
{{--                    <span class="notification-badge">3</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <img src="https://via.placeholder.com/40" alt="User Avatar" class="user-avatar">--}}
{{--        </div>--}}
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="filter-section animate-fade-slide">
            <div>
                <label for="dateRange">Date Range:</label>
                <select id="dateRange">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>This Quarter</option>
                    <option>This Year</option>
                    <option>Custom Range</option>
                </select>
            </div>
            <div>
                <label for="statusFilter">Status:</label>
                <select id="statusFilter">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label for="supervisorFilter">Supervisor:</label>
                <select id="supervisorFilter">
                    <option value="">All</option>
                    <option value="1">Dr. Smith</option>
                    <option value="2">Prof. Jones</option>
                </select>
            </div>
        </div>

        <div class="three-column-section animate-fade-slide">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-gray-700 text-sm font-medium">Total Submitted Topics (School 1)</h2>
                        <p class="text-3xl font-semibold text-blue-600">60</p>
                        <p class="text-xs text-green-500 flex items-center">
                            <i class="fas fa-arrow-up trend-arrow"></i> 5% this week
                        </p>
                    </div>
                    <i class="fas fa-file-alt text-blue-400 text-3xl"></i>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-gray-700 text-sm font-medium">Total Submitted Topics (School 2)</h2>
                        <p class="text-3xl font-semibold text-blue-600">50</p>
                        <p class="text-xs text-red-500 flex items-center">
                            <i class="fas fa-arrow-down trend-arrow"></i> 2% this week
                        </p>
                    </div>
                    <i class="fas fa-file-alt text-blue-400 text-3xl"></i>
                </div>
            </div>

            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-gray-700 text-sm font-medium">Total Submitted Topics (School 3)</h2>
                        <p class="text-3xl font-semibold text-blue-600">40</p>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-arrow-right trend-arrow"></i> No change
                        </p>
                    </div>
                    <i class="fas fa-file-alt text-blue-400 text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6 mb-6 animate-fade-slide">
            <div class="card">
                <h2 class="text-gray-700 font-semibold mb-4">Topic Submission Trend</h2>
                <div class="chart-container">
                    <canvas id="topicSubmissionChart"></canvas>
                </div>
            </div>
            <div class="card">
                <h2 class="text-gray-700 font-semibold mb-4">Approval vs. Rejection Rate</h2>
                <div class="chart-container">
                    <canvas id="approvalRejectionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card animate-fade-slide">
            <h2 class="text-gray-700 font-semibold mb-4">Recent Topic Submissions</h2>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Topic Title</th>
                        <th>Submitted By</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Developing a Smart Recommendation System</td>
                        <td>John Doe</td>
                        <td>2025-04-07</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td class="table-actions">
                            <a href="#"><i class="fas fa-eye"></i></a>
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Analysis of Machine Learning Algorithms for Image Recognition</td>
                        <td>Jane Smith</td>
                        <td>2025-04-06</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td class="table-actions">
                            <a href="#"><i class="fas fa-eye"></i></a>
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>The Impact of Social Media on Political Campaigns</td>
                        <td>Peter Jones</td>
                        <td>2025-04-05</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                        <td class="table-actions">
                            <a href="#"><i class="fas fa-eye"></i></a>
                            <a href="#"><i class="fas fa-edit"></i></a>
                            <a href="#"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Topic Submission Trend Chart
            const topicSubmissionChartCtx = document.getElementById('topicSubmissionChart').getContext('2d');
            const topicSubmissionChart = new Chart(topicSubmissionChartCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Submitted Topics',
                        data: [20, 35, 45, 30, 55, 60, 70, 65, 80, 75, 90, 85],
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Approval vs. Rejection Rate Chart
            const approvalRejectionChartCtx = document.getElementById('approvalRejectionChart').getContext('2d');
            const approvalRejectionChart = new Chart(approvalRejectionChartCtx, {
                type: 'pie',
                data: {
                    labels: ['Approved', 'Rejected', 'Pending'],
                    datasets: [{
                        data: [110, 15, 25],
                        backgroundColor: ['#22c55e', '#ef4444', '#facc15'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Filter interactivity (basic example)
            const statusFilter = document.getElementById('statusFilter');
            const supervisorFilter = document.getElementById('supervisorFilter');
            const dataTable = document.querySelector('.data-table tbody');

            function applyFilters() {
                const statusValue = statusFilter.value;
                const supervisorValue = supervisorFilter.value;

                const rows = dataTable.querySelectorAll('tr');
                rows.forEach(row => {
                    let display = true;

                    // Status filter
                    if (statusValue) {
                        const statusCell = row.querySelector('td:nth-child(4) span'); // Get the status cell
                        if (statusCell) {
                            const statusText = statusCell.textContent.toLowerCase();
                            if (!statusText.includes(statusValue)) {
                                display = false;
                            }
                        }
                        else{
                            display = false;
                        }
                    }

                    // Supervisor filter (Add this part when you have supervisor data in your table)
                    // if (supervisorValue) {
                    //     const supervisorCell = row.querySelector('td:nth-child(5)'); // Adjust the column number
                    //     if (supervisorCell) {
                    //         const supervisorText = supervisorCell.textContent.toLowerCase();
                    //         if (!supervisorText.includes(supervisorValue)) {
                    //             display = false;
                    //         }
                    //     }
                    // }

                    row.style.display = display ? '' : 'none';
                });
            }
            statusFilter.addEventListener('change', applyFilters);
            supervisorFilter.addEventListener('change', applyFilters);
        });
    </script>
@endsection
