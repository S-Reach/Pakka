@extends('layout.master')

@section('content')

<title>Copyright Policy</title>
<link rel="stylesheet" href="{{ asset('css/legal/copyright-policy.css') }}">


<div class="legal-container">

<h1 class="legal-title">Copyright Policy</h1>

<div class="legal-date">Last updated: June 16, 2026</div>

<div class="legal-section">

<h3>1. Ownership of Content</h3>

<p>
All original content created by users on Pakka belongs to the creator.
Authors keep full ownership of their stories and written work.
</p>

</div>

<div class="legal-section">

<h3>2. Platform License</h3>

<p>
By publishing content on Pakka, you allow us a non-exclusive license to display,
store, and distribute your content within the platform.
</p>

</div>

<div class="legal-section">

<h3>3. Copyright Violation</h3>

<p>
You must not upload or publish content that belongs to someone else without permission.
</p>

<ul>
<li>No copying stories from other authors.</li>
<li>No reposting copyrighted books or chapters.</li>
<li>No plagiarism of any kind.</li>
</ul>

</div>

<div class="legal-section">

<h3>4. DMCA / Takedown</h3>

<p>
If you believe your copyrighted work has been used without permission,
you can request removal by contacting us.
We will review and remove violating content if necessary.
</p>

</div>

<div class="legal-section">

<h3>5. Repeat Violations</h3>

<p>
Users who repeatedly violate copyright rules may have their accounts suspended or permanently banned.
</p>

</div>

<div class="legal-section">

<h3>6. Contact</h3>

<p>
Copyright issues: pakkasupport@gmail.com
</p>

</div>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

</div>

@endsection