#!/bin/bash

# This function is responsible to create thumb images using convert command and then kill the process of convert command by process id if it exists
# Return - it will return the status of convert command i.e. zero or non-zero value
checkImageMagickConvertProcess()
{
	# Run the convert command with arguments
	# It will create the thumbs as per argument values
	convert $1 
		
	# Get process id of convert command in variable 'PID'
	# This process id of convert command is needed to check/kill the process
	PID=$$ 
	
	# Get status of convert command in variable 'status'
	# 0 - For success 
	# non-zero - For failure
	status=$?
	
	# To check if the process is still exists
	# Here it kills the process if it exists
	if ps -p $PID >&- ; then
		kill -9 $PID	# Kill the process
	fi		
	
	return $status
}

# Get arguments which are passed from PHP into the variable 'arguments'
# This argument should contain the images details like source image, target image, crop values etc.
arguments=$@ 

# Call the function checkImageMagickConvertProcess and pass the $arguments to this function
checkImageMagickConvertProcess "$arguments"

# Get return value from function into the variable 'returnVal'
returnVal=$?

# Exit with return value
exit $returnVal
