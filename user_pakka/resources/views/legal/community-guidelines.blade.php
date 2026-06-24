@extends('layout.master')

@section('content')

<title>Community Guidelines</title>
<link rel="stylesheet" href="{{ asset('css/legal/community-guidelines.css') }}">

<div class="legal-container">

<h1 class="legal-title">Community Guidelines</h1>

<div class="legal-date">Last updated: June 16, 2026</div>

<div class="legal-section">

<h3>1. Respect Others</h3>

<p>
Pakka is a creative writing community. All users must treat others with respect.
Harassment, bullying, or abusive language is not allowed.
</p>

</div>

<div class="legal-section">

<h3>2. Positive Environment</h3>

<ul>
<li>Be polite in comments and reviews.</li>
<li>Give constructive feedback to authors.</li>
<li>Do not insult or attack other users.</li>
</ul>

</div>

<div class="legal-section">

<h3>3. Content Behavior</h3>

<p>
Users must ensure their content is appropriate and safe for the community.
</p>

<ul>
<li>No hate speech or discrimination.</li>
<li>No violent or harmful content.</li>
<li>No sexual content involving minors.</li>
</ul>

</div>

<div class="legal-section">

<h3>4. Spam & Misuse</h3>

<ul>
<li>No spam comments or fake engagement.</li>
<li>No repeated posting of irrelevant content.</li>
<li>No manipulation of ratings or reviews.</li>
</ul>

</div>

<div class="legal-section">

<h3>5. Account Safety</h3>

<p>
Do not impersonate other users or share false identity information.
Keep your account secure at all times.
</p>

</div>

<div class="legal-section">

<h3>6. Enforcement</h3>

<p>
Violating these guidelines may result in content removal, temporary suspension, or permanent account ban.
</p>

</div>

<div class="legal-section">

<h3>7. Contact</h3>

<p>
Report violations to: pakkasupport@gmail.com
</p>

</div>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

</div>

@endsection