#!/bin/bash

# This creates and signs a .tar.gz file with a valid private key on your keyring.
#
# Params: sign.sh /location/of/module <key_base64>
#
# If key_base64 is supplied, it will be decoded and used as the GPG key.

if [ ! "$1" ] || [ ! "$2" ] || [ ! "$3" ]; then
    echo "$0: /location/of/module <key_base64> <exclude_file>"
    exit 1
fi

parse_xml_value() {
    local file="$1"
    local element="$2"
    xmlstarlet sel -t -v "/module/$element" "$file"
}

getHashes() {
    local dir="$1"
    local exclude_patterns="$2"

    if [ ! -d "$dir" ]; then
        echo "getHashes was given $dir which is not a directory!"
        exit 1
    fi

    # Use find + grep -v to filter based on exclude patterns
    while IFS= read -r -d '' file; do
        if [[ ! $file =~ $exclude_patterns ]]; then
            local hash
            hash=$(sha256sum "$file" | awk '{print $1}')
            printf "%s = %s\n" "${file#$dir/}" "$hash"
        fi
    done < <(find "$dir" -type f -print0)
}

loc="$1"
key_base64="$2"
keyindex=2
exclude_file="$3"
version=$(parse_xml_value "$loc/module.xml" "version")
rawname=$(parse_xml_value "$loc/module.xml" "rawname")
tarball_name="$rawname-$version.tar.gz"

# Validate if version and rawname are extracted
if [ -z "$version" ] || [ -z "$rawname" ]; then
    echo "Error: Could not extract version or rawname from module.xml"
    exit 1
fi

# Load exclude patterns if provided
exclude_patterns=""
if [ -f "$exclude_file" ]; then
    exclude_patterns=$(cat "$exclude_file" | xargs -0 | awk '{print "|" $0}')
fi

if [ "${loc: -1}" == "/" ]; then
    # Strip off any trailing slash
    loc="${loc:0:-1}"
fi

if [ ! -d "$loc" ]; then
    echo "$loc is not a directory"
    exit 1
fi

if [ ! -e "$loc/module.xml" ]; then
    echo "module.xml does not exist in $loc"
    exit 1
fi

# Decode the base64 encoded key
key=$(echo "$key_base64" | base64 -d)

# Import the decoded key into GPG
echo "Importing GPG key..."
echo "$key" | gpg --import -

sig="$loc/module.sig"

echo "Signing with provided key"
echo -ne "\tGenerating file list..."
rm -f "$sig"
files=$(getHashes "$loc" "$exclude_patterns")
echo ""
echo -ne "\tSigning $sig.."
{
    echo ";################################################"
    echo "#        FreePBX Module Signature File         #"
    echo ";################################################"
    echo "# Do not alter the contents of this file!  If  #"
    echo "# this file is tampered with, the module will  #"
    echo "# fail validation and be marked as invalid!    #"
    echo ";################################################"
    echo ""
    echo "[config]"
    echo "version=2"
    echo "hash=sha256"
    echo "signedwith=$key"
    echo "signedby=sign.php"
    echo "repo=manual"
    echo "type=public"
    echo "[hashes]"
    echo "$files"
    echo ";# End"
} > >(gpg --default-key "268C8DD0" --clearsign >"$sig")
echo ""
echo -ne "\tCreating tarball $tarball_name..."
tar -czf "$tarball_name" -X "$exclude_file" "$loc"
echo ""
echo "Done"
