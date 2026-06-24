@extends('layout.master')

@section('content')

<link rel="stylesheet" href="{{ asset('css/legal/privacy.css') }}">

<div class="legal-container">

<h1 class="legal-title">Privacy Policy</h1>

<div class="legal-date">
Last updated: June 15, 2026
</div>

<div class="legal-section">

<h3>1. Introduction</h3>

<p>
At Pakka, we respect your privacy and are committed to protecting your personal data.
This Privacy Policy explains what information we collect, how we use it, and your rights when using our platform.
</p>

</div>

<div class="legal-section">

<h3>2. Information We Collect</h3>

<p>
We collect information that you provide directly and information collected automatically when you use Pakka.
</p>

<ul>

<li><b>Account Information:</b> username, email address, password (encrypted).</li>

<li><b>Profile Information:</b> display name, bio, and profile picture (optional).</li>

<li><b>Content Data:</b> stories, chapters, comments, and reviews you publish.</li>

<li><b>Payment Data:</b> withdrawal details such as bank/QR payment information (we do not store full card details).</li>

<li><b>Usage Data:</b> reading history, pages visited, and platform activity.</li>

<li><b>Device Data:</b> browser type, device type, and operating system.</li>

</ul>

</div>

<div class="legal-section">

<h3>3. How We Use Your Information</h3>

<p>
We use your information to operate and improve the Pakka platform.
</p>

<ul>

<li>Create and manage your account.</li>

<li>Provide reading and writing features.</li>

<li>Personalize story recommendations.</li>

<li>Process payments and writer withdrawals.</li>

<li>Monitor platform security and prevent abuse.</li>

<li>Send important updates and notifications.</li>

<li>Improve performance and user experience.</li>

</ul>

</div>

<div class="legal-section">

<h3>4. Sharing of Information</h3>

<p>
We do not sell or trade your personal information.
However, we may share limited data in the following cases:
</p>

<ul>

<li><b>Public Content:</b> your username, stories, comments, and reviews are visible to other users.</li>

<li><b>Service Providers:</b> trusted third-party services for hosting, analytics, or payment processing.</li>

<li><b>Legal Requirements:</b> when required by law or to protect platform safety.</li>

</ul>

</div>

<div class="legal-section">

<h3>5. Data Security</h3>

<p>
We take security seriously and use industry-standard protection methods such as encryption, secure servers, and access controls.
However, no system is completely secure, and we cannot guarantee absolute protection.
</p>

</div>

<div class="legal-section">

<h3>6. Data Retention</h3>

<p>
We keep your data as long as your account is active.
If you delete your account, we will remove or anonymize your data within 30 days, unless required for legal or financial purposes.
</p>

</div>

<div class="legal-section">

<h3>7. Your Rights</h3>

<p>
You have control over your personal data and may request:
</p>

<ul>

<li>Access to your personal information.</li>

<li>Correction of incorrect data.</li>

<li>Deletion of your account and data.</li>

<li>A copy of your stored data.</li>

<li>Restriction of certain processing activities.</li>

</ul>

</div>

<div class="legal-section">

<h3>8. Children's Privacy</h3>

<p>
Pakka is not intended for children under the age of 13.
We do not knowingly collect data from children under this age.
If we discover such data, we will delete it immediately.
</p>

</div>

<div class="legal-section">

<h3>9. Cookies</h3>

<p>
We use cookies to improve user experience, remember login sessions, and analyze platform performance.
You can disable cookies in your browser, but some features may not work properly.
</p>

</div>

<div class="legal-section">

<h3>10. Changes to This Policy</h3>

<p>
We may update this Privacy Policy from time to time.
When changes are made, we will update the “Last updated” date.
Continued use of Pakka means you accept the updated policy.
</p>

</div>

<div class="legal-section">

<h3>11. Contact Us</h3>

<p>
If you have any questions about this Privacy Policy, you can contact us at:
</p>

<p>
<strong>pakkasupport@gmail.com</strong>
</p>

</div>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

</div>

@endsection