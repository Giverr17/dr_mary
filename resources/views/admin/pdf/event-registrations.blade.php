<!DOCTYPE html>
<html>
<head>
    <title>Event Registrations - {{ $event->title }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #C9A84C; padding-bottom: 10px; }
        .event-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Event Registration Report</h1>
        <p>Dr. Mary Management Portal</p>
    </div>

    <div class="event-info">
        <h3>{{ $event->title }}</h3>
        <p><strong>Date:</strong> {{ $event->date_start->format('M d, Y') }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Total Registrations:</strong> {{ $registrations->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Organization / Job Title</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $reg)
            <tr>
                <td>{{ $reg->created_at->format('M d, Y') }}</td>
                <td>{{ $reg->full_name }}</td>
                <td>{{ $reg->email }}</td>
                <td>
                    {{ $reg->organization }} <br>
                    <small>{{ $reg->job_title }}</small>
                </td>
                <td>{{ $reg->message }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html>
