<div style="font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1B2A4A; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #C9A84C;">
        <h1 style="font-family: 'Playfair Display', serif; color: #1B2A4A; margin: 0;">New Subscription Request</h1>
        <p style="color: #C9A84C; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-top: 5px;">Dr. Uhunoma M. Isibor</p>
    </div>

    <div style="background: #fff; padding: 10px; text-align: center;">
        <p style="font-size: 16px; margin-bottom: 20px;">A new user has subscribed to the newsletter:</p>
        <p style="font-size: 18px; font-weight: bold; color: #C9A84C; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: inline-block;">{{ $email }}</p>
        <p style="font-size: 14px; margin-top: 30px;">You can view and manage all subscribers from the admin dashboard.</p>
    </div>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #64748b; font-size: 12px;">
        <p>© {{ date('Y') }} Dr. Uhunoma M. Isibor. All rights reserved.</p>
        <p>This message was sent automatically from your website.</p>
        <p><a href="{{ url('/manage/newsletter') }}" style="color: #C9A84C; text-decoration: none; font-weight: bold;">Go to Admin Portal</a></p>
    </div>
</div>