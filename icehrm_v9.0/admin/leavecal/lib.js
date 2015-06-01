/*
This file is part of iCE Hrm.

iCE Hrm is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

iCE Hrm is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with iCE Hrm. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------

Original work Copyright (c) 2012 [Gamonoid Media Pvt. Ltd]  
Developer: Thilina Hasantha (thilina.hasantha[at]gmail.com / facebook.com/thilinah)
 */

function EmployeeLeaveCalendarAdapter(endPoint) {
	this.initAdapter(endPoint);
}

EmployeeLeaveCalendarAdapter.inherits(AdapterBase);



EmployeeLeaveCalendarAdapter.method('getDataMapping', function() {
	return [];
});

EmployeeLeaveCalendarAdapter.method('getHeaders', function() {
	return [];
});

EmployeeLeaveCalendarAdapter.method('getFormFields', function() {
	return [];
});


EmployeeLeaveCalendarAdapter.method('get', function(callBackData) {
});

EmployeeLeaveCalendarAdapter.method('getLeaveJsonUrl', function() {
	var url = this.moduleRelativeURL+"?a=ca&sa=getLeavesForMeAndSubordinates&t="+this.table+"&mod=admin%3Dleavecal";
	return url;
});


EmployeeLeaveCalendarAdapter.method('getHelpLink', function () {
	return 'http://blog.icehrm.com/?page_id=115';
});
