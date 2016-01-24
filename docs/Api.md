###

<h2>Rest API</h2>

<p>The API is accessible via this endpoint:</p>
<code>https://www.example.com/pi-api/v1

<h2>Request/Response</h2>
<p>The default response format is JSON. Requests with a message body use plain JSON to fill the DTOs as well query parameters.</p>
<p>Some general information about responses:</p>
<ul>
<li>Dates are returned in RFC3339 format in UTC timezone: YYYY-MM-DDTHH:MM:SSZ</li>
<li>Any decimal monetary amount are returned as strings with two decimal places.</li>
</ul>
<h2>Filters</h2>
<table>
	<thead>
		<tr>
			<th>Filter</th>
			<th>Description</th>
			<th>Example</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>created_at_min</td>
			<td>only resources created after the provided date</td>
			<td>/?created_at_min=2014-12-24</td>
		</tr>
		<tr>
			<td>created_at_max</td>
			<td>only resources created before the provided date</td>
			<td>/?created_at_max=2014-12-24</td>
		</tr>
		<tr>
			<td>updated_at_min</td>
			<td>only resources updated after the provided date</td>
			<td>/?updated_at_min=2014-12-24</td>
		</tr>
		<tr>
			<td>updated_at_max</td>
			<td>only resources updated before the provided date</td>
			<td>/?updated_at_max=2014-12-24</td>
		</tr>
		<tr>
			<td>q</td>
			<td>performs a keyword search. resources may implement more than the name for keywords, as categories and tags</td>
			<td>/?q=what%20are%roses</td>
		</tr>
		<tr>
			<td>order</td>
			<td>controls the ordering of the resources returned</td>
			<td>/?order=asc</td>
		</tr>
		<tr>
			<td>orderby</td>
			<td>controls the field used for ordering the resources returned. Defaults to date</td>
			<td>/?order=asc</td>
		</tr>
		<tr>
			<td>status</td>
			<td>controls the status of the resources returned</td>
			<td>/?status=published</td>
		</tr>
		<tr>
			<td>pagination</td>
			<td>see bellow</td>
			<td></td>
		</tr>
	</tbody>
</table>
<h3>Fields Parameters</h3>
<p>The field filter allow to limit the fields returned in the response. Multiple fields are separate with commas:</p>
<code>GET /products?fields=id,name</code>
<p>For resources with embed resources you may specify the sub fields with dot-notation:</p>
<code>GET /products?fields=id,address.cep</code>
<h2>Pagination</h2>
<p>Requests that return multiple resources will be paginated to 10 items by default.</p>
<p>Page is specified with page filter</p>
<code>GET /products?page=2</code>
<p>Others filters: offset and limit</p>
<p>The total number of resources and pages are always included in the <code>X-Pi-Total</code> and <code>X-Pi-TotalPages</code> HTTP headers.</p>
<h2>Errors</h2>
<p>Errors are categorized in:</p>
<ul>
<li>Fatal - not expected, could cause application failure</li>
<li>Error - normal errors, expected by application</li>
<li>Warn - indicates that a situation is expected to happen, usually when requests take too long, resources are on the limit</li>
</ul>
<p>If an error occur during an request, the  <b>error</b> field is populated. The response may contain the expected result as well.</p>
<table>
	<thead>
		<tr>
			<th>Type</th>
			<th>Description</th>
			<th>Example</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>error_code<td>
			<td>error code as described in <a href="">error codes table</a></td>
			<td>500</td>
		</tr>
		<tr>
			<td>error_message</td>
			<td>message describing the error, auditable for user display</td>
			<td>the field <b>Description</b> must have at leas 100 characters, only 87 were provided.</td>
		</tr>
		<tr>
			<td>error_description</td>
			<td>message describing the error in trace level, not auditable for user display</td>
			<td></td>
		</tr>
	</tbody>
</table>